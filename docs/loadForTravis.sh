git clone https://github.com/c-harris/php-profiler-extension.git
pushd php-profiler-extension
phpize
./configure
make
make install
 echo "extension = algoweb.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
popd
