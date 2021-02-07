FROM debian
RUN apt-get update && apt-get -y install wget net-tools
RUN wget -q https://www.apachefriends.org/xampp-files/8.0.1/xampp-linux-x64-8.0.1-1-installer.run
RUN chmod +x xampp-linux*.run && ./xampp-linux*.run --mode unattended
CMD /opt/lampp/lampp start && tail -F /dev/log
