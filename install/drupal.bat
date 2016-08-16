cd \home\site
echo $client = new-object System.Net.WebClient > download.ps1
echo $client.DownloadFile("https://ftp.drupal.org/files/projects/drupal-8.1.8.zip","drupal.zip") >> download.ps1
powershell -file download.ps1
cd wwwroot
unzip ..\drupal.zip
