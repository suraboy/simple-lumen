pipeline {
    agent {
        docker {
            image 'php:7.0'
            args '-u root:sudo'
        }
    }
    stages {
        stage('Build') {
            steps {
                /**
                 * Install packages
                 */
                sh '''apt-get update -q
                apt-get install git -y
                apt-get autoremove graphviz -y
                apt-get install graphviz -y
                '''

                /**
                 * Install composer
                 */
                sh '''
                    echo $USER
                    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
                    php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
                    php composer-setup.php
                    php -r "unlink('composer-setup.php');"
                    php composer.phar self-update
                    php composer.phar install --no-interaction
                    ls -la
                    vendor/bin/phpunit
                '''
            }
        }
    }
}
