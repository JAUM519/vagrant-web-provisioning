Vagrant.configure("2") do |config|
  # Máquina Web
  config.vm.define "web" do |web|
    web.vm.box = "bento/ubuntu-20.04"
    web.vm.hostname = "web"
    web.vm.network "private_network", ip: "192.168.56.19"
    web.vm.provision "shell", path: "provision-web.sh"
  end

  # Máquina DB (reto)
  config.vm.define "db" do |db|
    db.vm.box = "bento/ubuntu-20.04"
    db.vm.hostname = "db"
    db.vm.network "private_network", ip: "192.168.56.20"
    db.vm.provision "shell", path: "provision-db.sh"
  end
end
