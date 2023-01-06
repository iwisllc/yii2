### Simple queues system for whom don`t want using external managers like RabbitMQ.

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

You are able to run multiple instants of worker separate by Target like
`./yii job/worker 0 Test` - where 0 is timeout, and Test is a target worker placed in directory `./common/worker/Workers/Test.php`

### How its works and how its use

`./yii job/worker` observe database using iterator `ARIterator`. When in table `jobs` appear record, class `Job` execute worker class described in field `destination_id`. All exceptions are catching, and depending on this task may be delayed or ignored.

You may use this in you project for sending emails or executing some tasks which must be executed undepended from frontend.

When you create new task you able to set time of execute. We recommend create helper for easily adding new tasks. For example `JobHelper::doSendLetter($data, $sendDateTime)`.

System also send emergency emails in case job fail. By default, it sends on email which set in `./common/config/params.php` in field `adminEmail`.