## udemy Laravel講座

## インストール方法

## インストール後の実施事項

画像のダミーデータは
public/imagesフォルダ内に
sample1.jpg ～ sample6.jpg として
保存しています。

php artisan storage:link で
storageフォルダにリンク後、

storage/app/public/productsフォルダ内に
保存すると表示されます。

(productsフォルダがない場合は作成してください。)

```shell
mkdir -p storage/app/public/products
php artisan storage:link
chmod -R 775 storage
chmod -R 775 bootstrap/cache
cp public/images/sample*.jpg storage/app/public/products/
```


ショップの画像も表示する場合は
storage/app/public/shopsフォルダを作成し、
画像を保存してください。

```shell
mkdir -p storage/app/public/shops
cp public/images/sample1.jpg storage/app/public/shops/
cp public/images/sample2.jpg storage/app/public/shops/
```
