#!/usr/bin/env bash

# Executed on the first start or provisioning of the box
if [ "$1" = "first-up" ]
then

	# set correct permissions for private key
	chmod 0700 /home/vagrant/.ssh
	chmod 0600 /home/vagrant/.ssh/id_rsa
	chmod 0600 /home/vagrant/.ssh/config

	# creating logs directory
	mkdir -p /vagrant/build/logs

exit
fi

# Executed on every start of the box
if [ "$1" = "regular-up" ]
then

	# update composer
	echo -e "\e[0mUpdating composer...\e[1;32m"
	composer self-update

	# restart php7.1-fpm
	echo -e "\e[0mRestarting service php7.1-fpm...\e[1;32m"
	service php7.1-fpm restart

exit
fi
