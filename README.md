# Docker_webtest
Some tests using Docker on WAGO Controllers (PFC200 G2, TP600 and edge controllers). 
Here is an example of nginx webserver (port 8001) with php5 (with mysqli), MySQL database (port 3306) and MQTT Broker (port 1884 and websockets 9001).

HOW TO:
=======
1. Install Docker: https://github.com/WAGO/docker-ipk
2. Add docker-compose: https://github.com/WAGO/docker-compose
3. Copy all files to the controller (using ftp of ssh client)
4. Connect via SSH (www.putty.org) to the controller and run command *"docker-compose up -d"* in catalog where docker-compose file is.
5. Check http://<controller_ip>:8001
