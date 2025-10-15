# SECURE

TODO: これも古い
TODO: これはaws。gcpとか他でもいけるように

- secretsをコードに書かない
	- github secrets, aws secret manager, aws parameter store (secrets), 等を利用する
	- terraformに平文で書かない。どうしても必要な場合はterraformリポジトリを別に管理し利用者を極限まで絞る
- ログでsecrets吐かない
	- envにも残さないように。＞上記secrets扱うサービス利用すれば残らない
	- terraform.tfstateにも残ったりするのを考えておくこと
- 開発、検証、本番等の各環境でVPCを分ける
	- 可能ならaws account自体を分ける
- リソースは外から直接参照させない
	- gatewayを作ってトンネルしないとアクセスできないようにする
	- エンジニアしか使わないなら日々自動で落とすevent仕込むとかする手も。
	- gateway作らない手もある。session manager。その方がよりよい
- 本番環境以外に本番のデータを置かない
	- 置きたい場合は機密情報をフィルタリングする＞メールアドレスや電話をxxxに変えるとか
- システム用のaws iam userのaccess keyは極力使わないか、最低限のpolicyにする
	- いまどきはaccess keyなくてもexecution roleでアクセスできる。s3とかは例外かも？
	- もし使う場合はfull accessは絶対に使わない。resourcesも可能な限り絞る
- iam MFA
	- https://docs.aws.amazon.com/ja_jp/IAM/latest/UserGuide/id_credentials_mfa_enable.html
- AWS Trusted Advisor
	- https://docs.aws.amazon.com/ja_jp/awssupport/latest/user/get-started-with-aws-trusted-advisor.html
- AWS Budgets
	- https://docs.aws.amazon.com/ja_jp/cost-management/latest/userguide/budgets-managing-costs.html
- CloudWatch で予想請求額をモニタリング
	- https://docs.aws.amazon.com/ja_jp/AmazonCloudWatch/latest/monitoring/gs_monitor_estimated_charges_with_cloudwatch.html#gs_creating_billing_alarm
- できたらやること
	- security hubの導入
		- https://docs.aws.amazon.com/ja_jp/securityhub/latest/userguide/securityhub-enable.html
	- guard dutyの導入
		- https://docs.aws.amazon.com/ja_jp/guardduty/latest/ug/guardduty_settingup.html
	- wafの導入
		-  https://docs.aws.amazon.com/ja_jp/waf/latest/developerguide/getting-started.html
	- aws cloudtrailの定期診断
		- https://docs.aws.amazon.com/ja_jp/awscloudtrail/latest/userguide/cloudtrail-user-guide.html
- 脆弱性診断について。リリース前実施だけでなく1年に1回実施する
	- 避難訓練
	- ハッキングしてみる。どんな侵入でどこまでの被害が及ぶか
	- 各リスク算出する。設計の見直し。
- 負荷試験
