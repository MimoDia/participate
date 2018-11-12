sudo rm -R core/;
sudo rm -R modules/contrib/;
sudo rm -R profiles/;
sudo rm -R themes/bootstrap/;
sudo rm -R vendor/;

composer install;

drush cim -y;

#compass clean themes/codingame/;
#compass compile themes/codingame/;

#drush ali config/lang/default-fr.po fr
drush cr;
