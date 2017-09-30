coding in /frontend is only for development, for production, upload index.php and views

Structuur:

  config: angular routing file
  controllers: angular controllers
  services: angular factories, data gathering, ajax, ...
  styles: less files

Start:
      - cd /frontend
      - npm install (als je nog geen node_modules hebt)
      - gulp (gaat vanzelf watcher op js en css files zetten) OF gulp run (build de js en css files, zonder watcher)


Gulp task manager:
      - less > css
      - js concat
      - js minify (angular dependency injection hardcoden = ["$scope", function($scope){}])
