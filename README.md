# Shimasuo

Shimasuoは後でバッチ処理するようなQueueもどきなもの
後回しにしたいのがPHPで書けるよ！！

## 必要そうなもの

	brew tap homebrew/dupes
	brew tap josegonzalez/homebrew-php
	brew install php54
	brew install composer

	brew install redis
	brew install php54-redis

インストールしたいプロジェクトのディレクトリに、
composer.jsonというファイル名で以下を記述。

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

ディレクトリへ移動してコンソールで、

	composer install

をすればインストールできます。

## Laravel 4で使ってみる

とてもとても残念ですがLaravel 4のQueueドライバには対応してません。

ですがprovidersとaliasesとartisanタスクを用意してみました。


### providersとaliases

app/config/app.php内のproviders、aliasesにそれぞれ登録を行う。

providers

	'Shimasuo\ShimasuoL4\ShimasuoServiceProvider',

aliases

	'Shimasuo'            => 'Shimasuo\ShimasuoL4\Shimasuo',


Facadeに対応と、SerializableClosure対応します。
こんな雰囲気で呼び出せます。

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

#### 終了

	php artisan shimasuo stop

startはプロセスを起動した後すぐ処理を返すので、

動作をじぃーーーっと見つめるには、exec引数で呼び出してください。

	php artisan shimasuo exec


## サンプル

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

### pushする

	// インスタンスメソッドを設定
	$queue->push([
		new Foo(), 'Method', ['p1', 'p2']
	]);

### 呼び出し
	// オートローダーなどの名前解決を行ったphpをコンソールとかで以下を呼び出すようにする。
	$queue->run();

