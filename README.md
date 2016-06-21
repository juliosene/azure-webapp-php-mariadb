# azure-webapp-php-mariadb
Azure WebApp running PHP with IaaS MariaDB version 10 cluster connected by VPN (database with private IP)

## Parameters:
* APPTOINSTALL - Aplication to install: Wordpress, Joomla, Magento, Moodle or nothing (clear install)
* PHPVERSION - version of PHP
* PROJECT_NAME - Short name for your project. (used to named your VMs, interfaces, security groups, etc)
* WORKERSIZE - Sise of work
* WEBAPPSVCPLANNAME - A name for your App Service Plan.
* WEBAPPSVCPLANSKU - Web App Service Plan.
* SKU - Web App SKU (basic instance)
* ADMINUSERNAME - admin user name for Linux instances (ssh login)
* ADMINPASSWORD - admin password for Linux instances (ssh login)
* DNSNAMEFORPUBLICWEBSITE - prefix for your website url (http://thisfield.azurewebsites.net)
* UBUNTUOSVERSION - Ubuntu version for VMs
* MYSQLROOTPASSWORD - Password for MariaDB root user
* MYSQLUSERPASSWORD - Password for webappuser. This password will be used to install your app!
* NODEDBCOUNT - Number of nodes in your MariaDB cluster (number of VMs)
* VMDBSIZE - Size of VMs for individual MariaDB node.

<a href="https://portal.azure.com/#create/Microsoft.Template/uri/https%3A%2F%2Fraw.githubusercontent.com%2Fjuliosene%2Fazure-webapp-php-mariadb%2Fmaster%2Fazuredeploy.json" target="_blank">
    <img src="http://azuredeploy.net/deploybutton.png"/>
</a>
<a href="http://armviz.io/#/?load=https%3A%2F%2Fraw.githubusercontent.com%2Fjuliosene%2Fazure-webapp-php-mariadb%2Fmaster%2Fazuredeploy.json" target="_blank">
    <img src="http://armviz.io/visualizebutton.png"/>
</a>

## After deployment finished, you need to make a small adjustment to sync VPN certificates. 
On Azure Portal (http://portal.azure.com):
* 1 – Go to your Web App, select All Settings -> Networking -> Click here to configure (VNET Integration). Observe that certificates are not in sync. Take a note of VNET NAME (you will reconnect on the same one)
    <img src="https://raw.githubusercontent.com/juliosene/azure-webapp-php-mariadb/master/docs/VPN-step01.png"/>
* 2 – Click on Disconnect option.
* 3 – Select Setup (VNET Integration) and choose your VNET.
    <img src="https://raw.githubusercontent.com/juliosene/azure-webapp-php-mariadb/master/docs/VPN-step02.png"/>
* 4 - Check if Azure Portal are adding your Virtual Network to Web App
    <img src="https://raw.githubusercontent.com/juliosene/azure-webapp-php-mariadb/master/docs/VPN-step03.png"/>
* 5 – Select Click here to configure (VNET Integration) and verify if Certificates are in sync.
    <img src="https://raw.githubusercontent.com/juliosene/azure-webapp-php-mariadb/master/docs/VPN-step04.png"/>

