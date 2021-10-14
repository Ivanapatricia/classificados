#!/bin/bash 

export JOOMLA_HOME=d:/wamp63/www/joomla

cp -Rf componsnts/* $JOOMLA_HOME/components/com_classificados
cp -Rf media/* $JOOMLA_HOME/media/com_classificados
cp -Rf administrator/components/com_classificados/* $JOOMLA_home/administrator/componsnts/com_classificados
