# 📧 Cpanel Email Manager

<div align="center">

![Email Manager](https://img.shields.io/badge/Email-Manager-blue?style=for-the-badge&logo=email&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)
![Self Hosted](https://img.shields.io/badge/Self-Hosted-orange?style=for-the-badge&logo=server&logoColor=white)

**A secure, self-hosted PHP application for managing cPanel email accounts without giving full panel access.**

*Perfect for web hosting providers, agencies, or teams who need safe email management delegation.*

</div>

---

## 📸 Screenshots

<div align="center">

![Dashboard Screenshot](/dashboard-screenshot.png)
*Clean, intuitive dashboard for email management*

![Setup Process](/setup-screenshot.png)
*One-click guided setup process*


</div>

---

## 🌟 Overview

Transform your email management workflow with a **secure**, **brandable**, and **user-friendly** interface that keeps your cPanel credentials safe while empowering your team or clients to manage email accounts effortlessly.

> **Why Choose This Tool?**  
> Eliminate the risk of sharing full cPanel access while maintaining complete control over your hosting environment.

---

## ✨ Key Features

<table>
<tr>
<td width="50%">

### 🔒 **Security First**
- **Isolated Access** - Users manage emails only
- **No cPanel Exposure** - Protected sensitive settings
- **Self-Hosted** - Complete data control

</td>
<td width="50%">

### 🎨 **Brand Experience**  
- **Custom Logo Upload** - Your brand front and center
- **Theme Customization** - Match your brand colors
- **Modern UI/UX** - Clean, responsive design

</td>
</tr>
<tr>
<td width="50%">

### ⚡ **Performance**
- **AJAX-Powered** - No page reloads
- **Real-Time Updates** - Instant feedback
- **Lightweight** - Fast loading times

</td>
<td width="50%">

### 🛠️ **Easy Setup**
- **One-Click Configuration** - Guided setup process
- **Auto Config Generation** - Creates `config.php` automatically
- **Zero Dependencies** - Upload and run

</td>
</tr>
</table>

---

## 🔧 System Requirements

<div align="center">

| Component | Requirement |
|-----------|-------------|
| **Web Server** | Apache, Nginx, or similar |
| **PHP Version** | 7.4 or higher |
| **Hosting** | cPanel-based account |
| **Storage** | Minimal (< 5MB) |

</div>

---

## 🚀 Quick Start Guide

### Step 1️⃣ **Download & Extract**
```bash
# Clone the repository
git clone https://github.com/Casey-Jeremy/email-manager.git

# Or download ZIP and extract
```

### Step 2️⃣ **Upload Files**
```
📁 Your Server
└── 📁 public_html/
    └── 📁 emails/           # Upload contents here
        ├── 📄 index.php
        ├── 📄 setup.php
        └── 📁 assets/       # Create this folder (755 permissions)
```

### Step 3️⃣ **Initialize Setup**
1. Navigate to: `https://yourdomain.com/emails/`
2. **Automatic Detection** - Setup page loads automatically
3. **Follow Wizard** - Enter cPanel credentials and branding
4. **Auto Configuration** - System creates `config.php`

### Step 4️⃣ **Security Hardening** *(Recommended)*
```bash
# Secure the configuration file
chmod 600 config.php
```

### Step 5️⃣ **Start Managing**
🎉 **You're ready!** Log in with your cPanel credentials and start managing emails.

---

## 🛡️ Security Features

- ✅ **Restricted API Access** - Only email-related cPanel functions
- ✅ **Session Management** - Secure authentication handling  
- ✅ **Input Validation** - Protected against common vulnerabilities
- ✅ **File Permissions** - Configurable security levels
- ✅ **No External Dependencies** - Reduced attack surface

---

## 🎯 Perfect For

<div align="center">

| **Web Hosting Providers** | **Digital Agencies** | **Development Teams** |
|:-------------------------:|:--------------------:|:--------------------:|
| Client email management | Client portal access | Team email delegation |
| Reduced support tickets | Branded experience | Secure collaboration |
| Maintained security | Professional image | Controlled access |

</div>

---

## 🤝 Contributing

We welcome contributions! Here's how you can help:

1. 🍴 **Fork** the repository
2. 🌿 **Create** a feature branch
3. ✨ **Add** your improvements
4. 🧪 **Test** thoroughly
5. 📝 **Submit** a pull request

---

## 📄 License

This project is licensed under the **MIT License** - see the full license for details.

```
MIT License - Free to use, modify, and distribute
```

---

<div align="center">

**Built with ❤️ for the hosting community**

[⭐ Star this repo](https://github.com/Casey-Jeremy/email-manager) • [🐛 Report Issues](https://github.com/Casey-Jeremy/email-manager/issues) • [💡 Request Features](https://github.com/Casey-Jeremy/email-manager/issues)

---

*Made something awesome with this tool? We'd love to hear about it!*

</div>
