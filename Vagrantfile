Vagrant.configure("2") do |config|

	# Neuer Box Name (automatisch die aktuellste Version)
	config.vm.box = "DreiWolt/devops007"

	# Download
	config.vm.box_download_insecure = true

	# Network und Hostnames
	config.vm.network "private_network", ip: "192.168.3.12"
	config.vm.hostname = "Types"

	# Provisioners
	# ------------

	# copy and link
	config.vm.provision "file", source: "env/vagrant/id_rsa", destination: "/home/vagrant/.ssh/id_rsa"
	config.vm.provision "file", source: "env/vagrant/ssh_config", destination: "/home/vagrant/.ssh/config"
	config.vm.provision "shell", path: "env/vagrant/bootstrap.sh", args: "first-up"
	config.vm.provision "shell", path: "env/vagrant/bootstrap.sh", args: "regular-up", run: "always"

end
