Simple queues system for whom don`t want using external managers like RabbitMQ.

Usage:

Pull repository, and prepare database. Then
```
composer update
```
after that
```
./init
```
configure database in config file `./common/config/main-local.php`
and
```
./yii migrate
```

After finish migration you can use following commands

`./yii job/test` - for create test task

`./yii job/worker` - main worker thread which process all workers

You able to run multiple instants of worker separate by Target like
`./yii job/worker 0 Test` - where 0 is timeout, and Test is a target worker placed in directory `./common/worker/Workers/Test.php`