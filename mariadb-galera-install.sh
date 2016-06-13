#!/bin/bash
# Install MariaDB Galera Cluster
#
# $1 - number of nodes; $2 - cluster name;
#
NNODES=${1-1}
MYSQLPASSWORD=${2:-""}
IPPREFIX=${3:-"10.0.0."}
DEBPASSWORD=${4:-`date +%D%A%B | md5sum| sha256sum | base64| fold -w16| head -n1`}
IPLIST=`echo ""`
MYIP=`ip route get ${IPPREFIX}70 | awk 'NR==1 {print $NF}'`
MYNAME=`echo "Node$MYIP" | sed 's/$IPPREFIX.1/-/'`
CNAME=${4:-"GaleraCluster"}
FIRSTNODE=`echo "${IPPREFIX}$(( $NNODES + 9 ))"`

for (( n=1; n<=$NNODES; n++ ))
do
   IPLIST+=`echo "${IPPREFIX}$(( $n + 9 ))"`
   if [ "$n" -lt $NNODES ];
   then
        IPLIST+=`echo ","`
   fi
done

cd ~
apt-get update > /dev/null
apt-get install -f -y > /dev/null
# apt-get upgrade -f -y
#apt-get dist-upgrade -f -y
# dpkg --configure --force-confnew -a

apt-get install lsb-release bc > /dev/null
REL=`lsb_release -sc`
DISTRO=`lsb_release -is | tr [:upper:] [:lower:]`
# NCORES=` cat /proc/cpuinfo | grep cores | wc -l`
# WORKER=`bc -l <<< "4*$NCORES"`

apt-get install -y --fix-missing python-software-properties > /dev/null
apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xcbcb082a1bb943db
add-apt-repository "deb http://mirror.edatel.net.co/mariadb/repo/10.1/$DISTRO $REL main"

apt-get update > /dev/null

DEBIAN_FRONTEND=noninteractive apt-get install -y rsync mariadb-server

# Remplace Debian maintenance config file

wget https://raw.githubusercontent.com/juliosene/azure-nginx-php-mariadb-cluster/master/files/debian.cnf > /dev/null

sed -i "s/#PASSWORD#/$DEBPASSWORD/g" debian.cnf
mv debian.cnf /etc/mysql/

mysql -u root <<EOF
GRANT ALL PRIVILEGES on *.* TO 'debian-sys-maint'@'localhost' IDENTIFIED BY '$DEBPASSWORD' WITH GRANT OPTION;
SET PASSWORD FOR 'root'@'localhost' = PASSWORD('$MYSQLPASSWORD');
CREATE USER 'root'@'%' IDENTIFIED BY '$MYSQLPASSWORD';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EOF

# To create another MariaDB root user:
#CREATE USER '$MYSQLUSER'@'localhost' IDENTIFIED BY '$MYSQLUSERPASS';
#GRANT ALL PRIVILEGES ON *.* TO '$MYSQLUSER'@'localhost' WITH GRANT OPTION;
#CREATE USER '$MYSQLUSER'@'%' IDENTIFIED BY '$MYSQLUSERPASS';
#GRANT ALL PRIVILEGES ON *.* TO '$MYSQLUSER'@'%' WITH GRANT OPTION;

service mysql stop

# adjust my.cnf
# sed -i "s/#wsrep_on=ON/wsrep_on=ON/g" /etc/mysql/my.cnf

# create Galera config file

wget https://raw.githubusercontent.com/juliosene/azure-nginx-php-mariadb-cluster/master/files/cluster.cnf > /dev/null

sed -i "s/#wsrep_on=ON/wsrep_on=ON/g;s/IPLIST/$IPLIST/g;s/MYIP/$MYIP/g;s/MYNAME/$MYNAME/g;s/CLUSTERNAME/$CNAME/g" cluster.cnf
mv cluster.cnf /etc/mysql/conf.d/

# Create a raid 
wget https://raw.githubusercontent.com/Azure/azure-quickstart-templates/master/shared_scripts/ubuntu/vm-disk-utils-0.1.sh
bash vm-disk-utils-0.1.sh -s
mkdir /datadisks/disk1/data
cp -R -p /var/lib/mysql /datadisks/disk1/data/
sed -i "s,/var/lib/mysql,/datadisks/disk1/data/mysql,g" /etc/mysql/my.cnf

# Starts a cluster if is the first node

if [ "$FIRSTNODE" = "$MYIP" ];
then
    service mysql start --wsrep-new-cluster > /dev/null
else
    service mysql start > /dev/null
fi

# To check cluster use the command below
# mysql -u root -p
# mysql> SELECT VARIABLE_VALUE as "cluster size" FROM INFORMATION_SCHEMA.GLOBAL_STATUS WHERE VARIABLE_NAME="wsrep_cluster_size";
# mysql> EXIT;
#
# To add a new cluster node:
# 1 - stop MariaDB
# service mysql stop
# 2 - start as a new node
# service mysql start --wsrep_cluster_address=gcomm://10.0.0.10
