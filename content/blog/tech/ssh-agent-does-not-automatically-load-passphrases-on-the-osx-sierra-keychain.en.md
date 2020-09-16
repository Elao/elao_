---
type:               "post"
title:              "SSH-agent does not automatically load passphrases on the OSX Sierra keychain during startup"
date:               "2016-10-14"
publishdate:        "2016-10-14"
draft:              false

description:        "SSH-agent does not automatically load passphrases on the OSX Sierra keychain during startup."

thumbnail:          "/images/posts/thumbnails/badass_vader.jpg"
header_img:         "/images/posts/headers/php_elao_code.jpg"
tags:               ["SSH", "OSX"]
categories:         ["Dev", "Tech", "OSX"]

author:    "gfaivre"
---

Some of us encountered an issue after upgrading to Mac OS Sierra.
Indeed right after it our ssh keys (**with passphrases**) were not forwarded to the remote hosts anymore.
<!--more-->
In previous versions of mac OSX, `ssh-agent` used to remember the passphrases for the keys added to the keychain (with `ssh-add -K`) and after a reboot (or logout/login), it automatically picked up the passphrases from the keychain with no extra step and it was perfect !

Unfortunately after upgrading to Sierra this way no longer works and command `ssh-add -K` in Sierra no longer saves SSH keys in OS's keychain.

__Apple Developer stated:__

> Engineering has determined that this issue behaves as intended based on the following information:
That’s expected. We re-aligned our behavior with the mainstream OpenSSH in this area.
You can fix this pretty easily by running ssh-add -A in your rc script if you want your keys to always be loaded.

We still can login to remote hosts via ssh **BUT** ssh keys with passphrase are not forwarded to the host which makes the agent somewhat useless.

# Doing it manually

If you are fine with the behavior and don't want to store your passphrases into keychain you can do it the old way by manually adding keys to the agent:

```
ssh-add -K /path/to/private/key
```
and to add identities to the agent using any passphrases stored in your keychain.

```
ssh-add -A
```

# Using ssh config file

A permanent (and probably the "cleanest") workaround to this behavior consists in using the new SSH option `AddKeysToAgent` option in your `.ssh/config` file as shown below

```shell
Host *
  AddKeysToAgent yes
```

N.B: This option does not add keys previously saved into the keychain __to the agent on boot;__ it adds keys to the agent **on use**.
In other words, the keys are not added to the agent until you actually use them.

Furthermore, this option is FreeBSD specific and will result an error on other Unix-like systems.

__You will have to re-enter your passphrase the first time you are using it after a logout or a reboot.__

# Using a «profile dot file»

For CLI users a partial solution is to add this to your dot files (`.zshrc`, `.bashrc`, `.bash_profile` ...).

```shell
ssh-add -K ~/.ssh/my_private_key &> /dev/null
ssh-add -A &> /dev/null
```


__This will only be effective when using terminal.__

# Using a launch agent (non-tested solution)

The last solution is to call command `ssh-add -A` on every startup of macOS.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>Label</key>
    <string>ssh-add-a</string>
    <key>ProgramArguments</key>
    <array>
        <string>ssh-add</string>
        <string>-A</string>
    </array>
    <key>RunAtLoad</key>
    <true/>
</dict>
</plist>
```
