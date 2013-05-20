# Shimasuo

Shimasuoはマルチプロセスで処理を行うPHP実装のJobQueueです。

Laravel4用のプロバイダが同梱されているので、

Laravel4からはより便利に利用できます。


## 使い方

以下の動作に必要なパッケージをHomeBrewなどでインストールします。

	brew tap homebrew/dupes
	brew tap josegonzalez/homebrew-php
	brew install php54
	brew install composer

	brew install redis
	brew install php54-redis


### composerを利用しインストールを行う

1. composer.jsonへ以下を追加する。

	```JSON
	{
		"repositories" : [
			{
				"packagist": false,
				"type": "vcs",
				"url": "git@github.com:na-apri/shimasuo.git"
			}
		],
   	 "require": {
			"na-apri/Shimasuo": "dev-master"
	    }
	}
	```

2. コンソールからinstallを行う。

		composer install



## Shmasuoの使い方

### 設定

	$queue = new \Shimasuo\Shimasuo([
		'connection' => [
			'config' => [
				'default' => [
					'host' => '127.0.0.1',
					'port' => '6379',
					'database' => '0',
				],
			],
			'task' => 'default',
			'worker' => 'default',
		],
		'key_prefix' => 'SAMPLE_',
	]);

### タスクの追加

	$queue->push([
		new Foo(), 'Method', ['p1', 'p2']
	]);

### タスクの呼び出し
	$queue->run();



## Laravel4での利用

ShimasuoはLaravel4のQueueドライバには対応してません。

ですが独自のproviderとFacadeとartisanタスクが用意されています。


### 設定ファイルのpublish

設定を変更するには以下のコマンドを実行し、

app/config/na-apri/Shimasuoに配置された設定ファイルに編集を行ってください。


		php artisan config:publish na-apri/Shimasuo
	

### providersとaliases

app/config/app.php内のproviders、aliasesにそれぞれ登録を行います。

#### 編集内容

- providers

		'Shimasuo\ShimasuoL4\ShimasuoServiceProvider',

- aliases

		'Shimasuo'            => 'Shimasuo\ShimasuoL4\Shimasuo',


Laravel4を利用する場合、aliasとSerializableClosure対応します。

#### 利用方法

		Shimasuo::push(
			function($p1, $p2) use ($useInstance){
				// Hard work
			},
			[$p1, $p2]
		);
	
### artisanでのタスク実行

コマンドラインのartisanからrunを実行したりプロセスを終了したりできます。

#### 実行

	php artisan shimasuo start


標準出力を確認したい場合などは、以下のコマンドを利用してください。

	php artisan shimasuo exec

php artisan shimasuo startも内部的にはexecを呼び出しています。


#### 終了

	php artisan shimasuo stop

