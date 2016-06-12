cd \home\site
echo $client = new-object System.Net.WebClient > download.ps1
echo $client.DownloadFile("https://wordpress.org/latest.zip","wordpress.zip") >> download.ps1
powershell -file download.ps1
cd wwwroot
unzip ..\wordpress.zip 
move wordpress phpapp
