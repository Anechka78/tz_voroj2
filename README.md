Make sure that your local server has PHP version 5.4.0 or higher and Composer installed. If they are not installed, download them from official websites and install them.

Create an empty folder for the project on your local server (tz_voroj2).

Create an nginx configuration file.
<b>Example:</b>

server {
	listen   80;
	listen 443 ssl;
	
	server_name tz_voroj2.loc;
	
	charset utf-8;
	client_max_body_size 128M;
	keepalive_timeout  120s;

	root path_to_sites_folder/tz_voroj2.loc/www/frontend/web;
	index index.php;
	
	ssl_certificate     /path_to_ssl_certificate/name_ssl_certificate.crt;
	ssl_certificate_key /path_to_ssl_certificate_key/name_ssl_certificate_key.key;

	access_log  /path_to_log_folder/access.log;
	error_log   /path_to_log_folder/error.log;
	
	location / {
		index index.php;
        try_files $uri $uri/ /index.php?$args;
    }
	
    location ~ \.php$ {
        fastcgi_param HTTPS on;
        include fastcgi_params;
		fastcgi_read_timeout 300;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		fastcgi_pass unix:/var/run/php-fpm/www.sock;
        fastcgi_index index.php;
    }

    location ~ /\.(ht|svn|git) {
        deny all;
    }
}

server {	
	listen   80;
	listen 443 ssl;
	
	server_name admin.tz_voroj2.loc;
	
	charset utf-8;
	client_max_body_size 128M;
	keepalive_timeout  120s;

	root path_to_sites_folder/tz_voroj2.loc/www/backend/web;
	index index.php;
	
	ssl_certificate     /path_to_ssl_certificate/name_ssl_certificate.crt;
	ssl_certificate_key /path_to_ssl_certificate_key/name_ssl_certificate_key.key;

	access_log  /path_to_log_folder/access.log;
	error_log   /path_to_log_folder/error.log;
	
	location / {
		index index.php;
        try_files $uri $uri/ /index.php?$args;
    }
	
    location ~ \.php$ {
        fastcgi_param HTTPS on;
        include fastcgi_params;
		fastcgi_read_timeout 300;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		fastcgi_pass unix:/var/run/php-fpm/www.sock;
        fastcgi_index index.php;
    }

    location ~ /\.(ht|svn|git) {
        deny all;
    }
}



<p>Create a database and privileges</p>
<p>Clone the Yii2 project repository from GitHub to the created folder tz_voroj2 using the command: git clone https://github.com/Anechka78/tz_voroj2.git</p>

In the console, go to the project's root folder and install the dependencies using the Composer command: composer install
In the configuration folders backend, frontend, and common, rename the files codeception-local.sample.php, main-local.sample.php, params-local.sample.php, test-local.sample.php, to codeception-local.php, main-local.php, params-local.php, test-local.php.
Change the configuration settings in these files according to your local configuration.

Run the database migrations using the command: ./yii migrate
If working on a local computer, add hosts
If working on a server, add the host's NS server at the registrar

<h1>Project functionality</h1>

p1. Generate users using the command: https://tz_voroj2.loc/site/generate
Create a database for RBAC: ./yii migrate --migrationPath=@yii/rbac/migrations/
Create roles and access rights in the console: ./yii rbac/init
If necessary, edit the init() method in RbacController beforehand.

p.2 Log in through the console using the command (./yii cron/get-token sullrich password), where 'sullrich' is the login and 'password' is the user's password, the script returns a token that is valid for 5 minutes.
p3. Open the frontend part of the site in a browser, go to the data entry section https://tz_voroj2.loc/data/put-data
<ul>
    <li>select the request type</li>
    <li>enter the access token from p2</li>
    <li>enter or generate a JSON object
    <b>example JSON object:</b>
    {"name":"Dr. Hassan Hill III","email":"deron70@kassulke.info","age":99,"address":{"street":"89811 Mayer Skyway","city":"East Edaburgh","state":"North Carolina","zip":"22367","phone":"1-202-262-3675","nested_array":{"illum":{"voluptas":{"odit":70,"aspernatur":"consequatur","dolor":95}},"pariatur":{"sunt":{"in":"nam","corrupti":"consequatur"},"id":{"laborum":27},"consequatur":{"quam":"tempora","qui":99},"unde":{"sunt":"suscipit"}},"ullam":{"voluptatibus":{"qui":"voluptates","quidem":44},"et":{"soluta":"aspernatur","enim":25,"vero":81,"quam":"temporibus","eveniet":"repudiandae"}},"ipsum":{"numquam":{"et":99,"asperiores":"laboriosam","harum":45},"tenetur":{"aperiam":56,"perspiciatis":12,"itaque":94,"perferendis":"tempore","ut":87},"quis":{"dolor":"rerum","earum":"est","fugiat":"officia","non":95},"asperiores":{"aut":40}}}}}</li>
</ul>
Then, submit the data.
<p>The response will either contain an error message with its description and code or a success message with the record ID, script execution time, and memory usage for the operation.</p>

p4. Open the frontend part of the site in a browser, go to the data update section https://tz_voroj2.loc/data/put-update-data
<ul>
    <li>select the request type</li>
    <li>enter the identifier of the record to modify</li>
    <li>enter the access token from p2</li>
    <li>enter the code to modify the JSON object in the format
    <b>example code:</b>
        $data->name = "John Smith";
        $data->age = 35;
        $data->address->street = "123 Main St";</li>
</ul>	
	Execute a request.
<p>In response, a message with an error description and code or a success message is received.</p>

p5. Open the backend part of the site in a browser, authorize with administrator rights, go to the data viewing section https://admin.tz_voroj2.loc/data/view/

    <p>To view the tree structure of a JSON object, click the eye icon. In the viewing mode, it is possible to collapse/expand individual elements.</p>
    <p>To delete a JSON object, click the trash can icon.</p>
