# Shimasuo

Shimasuoは後でバッチ処理するようなQueueもどきなもの

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
		"shimasuo": "dev-master"
    }
}
```

ディレクトリへ移動してコンソールで、

	composer install

をすればインストールできます。

## Laravel 4で使ってみる

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

