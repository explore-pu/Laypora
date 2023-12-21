# Lumen-Vue-Base

> 前后端基本开发集成，含登录认证

- 可单独部署，也可分布式部署(需redis)
- 前端使用 vite5 + vue3 + vue-router4 + pinia2 + axios + tailwindcss
- 后端使用 lumen v10

## 克隆

```shell
git clone https://github.com/explore-pu/Laypora.git
```

## 后端

```shell
# 安装依赖
composer install

# 复制.env
cp .env.example .env

# 创建key
php artisan key:generate

# 配置.env的数据库信息
# 数据迁移
php artisan migrate

# 数据初始化
php artisan db:seed
```

## 前端

```shell
# 安装依赖
npm install

# 开发编译
npm run dev

# watch编译
npm run watch

# 生产编译
npm run build
```

## apache .htaccess配置

```.htaccess
# Handle Front Controller...
RewriteCond %{REQUEST_URI} ^/api/
RewriteRule ^ index.php [L]

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.html [L]
```

## nginx 代理配置

```nginxconf
# vue代理
location / {
try_files $uri $uri/ /index.html;
}
# lumen代理
location /api/ {
try_files $uri $uri/ /index.php?$query_string;
}
```

## License

this is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
