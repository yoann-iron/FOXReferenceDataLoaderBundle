# A FAIRE





# BH-GENERATOR

[![Build Status](https://magnum.travis-ci.com/OUSERVERDEV/bh-generator.svg?token=ANUPkjqk8o5zJhPquRxz&branch=master)](https://magnum.travis-ci.com/OUSERVERDEV/bh-generator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OUSERVERDEV/bh-generator/badges/quality-score.png?b=master&s=6f4a383d6e4de80f26431d28dd537e275c674c50)](https://scrutinizer-ci.com/g/OUSERVERDEV/bh-generator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/OUSERVERDEV/bh-generator/badges/coverage.png?b=master&s=854daca83abb268a909b416b65e10c069402c102)](https://scrutinizer-ci.com/g/OUSERVERDEV/bh-generator/?branch=master)

## Installation

In order to install the generator, the service project and have a local environnement with a GP, please follow the instructions in the - [Ansible provisionning playbook](https://github.com/OUSERVERDEV/ansible-playbook-gen)


## Deploy

Requirements:

- bundler (may have been install during the previous step)
```bash
gem install bundler --user-install
```

Install:

```bash
bundle install --path vendor/bundle
# Setup folder structure (one time only / environment)
bundle exec cap staging deploy:setup
```

Deploy:

First time connect to staging (ask for the password) and add your public ssh key to `authorized_keys`:
```bash
ssh root@162.209.103.103
cd /var/www/.ssh
```

Then from your local computer:
```bash
bundle exec cap staging deploy
```

For the preprod environnement, follow the same steps with the adress 104.130.16.189 and for the production environnement use 162.242.218.230

## Provisioning

If you want to provision one single machine:
ansible-playbook -i provisioning/hosts/all provisioning/site.yml -u root --limit staging

Don't forget the --limit arguments otherwise you will provision all machines!!

#TODO: install bower and gulp in global

