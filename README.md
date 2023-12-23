# **故乡**

故乡是一个简易的视频分享搭建平台，旨在提供安全可靠的视频分享服务。我们基于 PHP 8.1-8.2 开发了这个项目，并采用了多个开源工具和库来提升安全性和用户体验。

### 技术栈

1. **AList**（[GitHub 地址](https://github.com/alist-org/alist)）
   - `AList` 是一个支持多种存储的文件列表程序，为我们提供了高效的后台交互能力。
2. **GoogleAuthenticator**（[GitHub 地址](https://github.com/PHPGangsta/GoogleAuthenticator)）
   - `Google Authenticator` 是一个用于与 `Google Authenticator` 移动应用进行交互的 `PHP` 类，实现了基于[RFC6238](https://tools.ietf.org/html/rfc6238)的 2 因素身份验证。
3. **Aura/Sql**（[GitHub 地址](https://github.com/auraphp/Aura.Sql)）
   - `Aura.Sql` 是一个强大的 `SQL` 查询构建库，为我们提供了可靠的数据库操作支持。
4. **Bootstrap**（[GitHub 地址](https://github.com/twbs/bootstrap)）
   - `Bootstrap` 是一个流行的前端框架，用于构建现代化的用户界面，提升了平台的用户体验和界面设计。
5. **ArtPlayer**（[GitHub 地址](https://github.com/zhw2590582/ArtPlayer)）
   - `ArtPlayer` 是一个视频播放器，已经内置到项目当中。（我们已经预留了一些设置，详细信息请查看官方文档）

### 网站配置注意事项

在配置网站时，请务必设置 URL 重写以确保正常运行。以下是一个示例配置（假设使用的是 Nginx）：

```Nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 视频播放问题解决提示

若遇到视频播放时只有声音没有图像的情况，这多半是由于浏览器编码支持问题所致。我们建议您尝试使用谷歌浏览器查看视频。

谷歌浏览器通常对于各种视频编码提供了更好的支持，可能能解决您遇到的声音无图像问题。另外，可以尝试在其他支持更多编码的浏览器中观看。

### 项目拉取及使用建议

为了方便项目的管理和维护，我们建议遵循以下步骤拉取项目：

1. 确保系统环境满足 PHP 8.1-8.2 的要求，或根据需要更改`Aura/Sql`的版本以适配您的 PHP 版本。
2. 使用 `composer` 工具，在 `inc` 文件夹内执行命令，主要安装 `Aura/Sql`。您可以在`composer.json`中更改版本。
3. 确保您的 PHP 已启用 `PDO` 以确保 `Aura/Sql` 正常使用，并提升系统安全性。
4. 项目支持自定义功能的添加，已预留相关接口。

### 项目发布及使用说明

考虑到新手使用的便利性，我们将主流的 PHP 版本打包放入发行版内，方便您的使用和部署。

### 许可协议

我们的项目采用了 GPL-3.0 许可协议。请在使用或修改该项目时遵循相应的协议规定。
