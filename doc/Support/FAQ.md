### Getting started
 - [How do I install LibreNMS?](#faq1)
 - [How do I add a device?](#faq2)
 - [How do I get help?](#faq3)
 - [What are the supported OSes for installing LibreNMS on?](#faq4)
 - [Do you have a demo available?](#faq5)

### Support
 - [Why do I get blank pages sometimes in the WebUI?](#faq6)
 - [How do I debug pages not loading correctly?](#faq7)

### Developing
 - [How do I add support for a new OS?](#faq8)
 - [What can I do to help?](#faq9)

#### <a name="faq1"> How do I install LibreNMS?</a>

This is currently well documented within the doc folder of the installation files.

For Debian / Ubuntu installs follow [Debian / Ubuntu](http://docs.librenms.org/Installation/Installation-(Debian-Ubuntu)

For RedHat / CentOS installs follow [RedHat / CentOS](http://docs.librenms.org/Installation/Installation-(RHEL-CentOS))

#### <a name="faq2"> How do I add a device?</a>

You have two options for adding a new device into LibreNMS.

 1. Using the command line via ssh you can add a new device by changing to the directory of your LibreNMS install and typing (be sure to put the correct details).

```ssh
./addhost.php [community] [v1|v2c] [port] [udp|udp6|tcp|tcp6]
```

 2. Using the web interface, go to Devices and then Add Device. Enter the details required for the device that you want to add and then click 'Add Host'.

#### <a name="faq3"> How do I get help?</a>

We have a few methods for you to get in touch to ask for help.

[Mailing List](https://groups.google.com/forum/#!forum/librenms-project)

[IRC](https://webchat.freenode.net/) Freenode ##librenms

[Bug Reports](https://github.com/librenms/librenms/issues)

#### <a name="faq4"> What are the supported OSes for installing LibreNMS on?</a>

Supported is quite a strong word :) The 'officially' supported distros are:

 - Ubuntu / Debian
 - Red Hat / CentOS
 - Gentoo

However we will always aim to help wherever possible so if you are running a distro that isn't one of the above then give it a try anyway and if you need help then jump on the irc channel.

#### <a name="faq5"> Do you have a demo available?</a>

We do indeed, you can find access to the demo [here](demo.librenms.org)

#### <a name="faq6"> Why do I get blank pages sometimes in the WebUI?</a>

The first thing to do is to add /debug=yes/ to the end of the URI (I.e /devices/debug=yes/).

If the page you are trying to load has a substantial amount of data in it then it could be that the php memory limit needs to be increased in php.ini and then your web service reloaded.

#### <a name="faq7"> How do I debug pages not loading correctly?</a>

A debug system is in place which enables you to see the output from php errors, warnings and notices along with the MySQL queries that have been run for that page.

To enable the debug option, add /debug=yes/ to the end of any URI (I.e /devices/debug=yes/) or ?debug=yes if you are debugging a graph directly.

You will then have a two options in the footer of the website - Show SQL Debug and Show PHP Debug. These will both popup that pages debug window for you to view. If the page itself has generated a fatal error then this will be displayed directly on the page.

#### <a name="faq8"> How do I add support for a new OS?</a>

The easiest way to show you how to do that is to link to an existing pull request that has been merged in on [GitHub](https://github.com/librenms/librenms/pull/352/files)

To go into a bit more detail, the following are usually needed:

**includes/definitions.inc.php**
Update this file to include the required definitions for the new OS.
**includes/discovery/os/ciscowlc.inc.php**
This file just sets the $os variable, done by checking the sysDescr snmp value for a particular value that matches the OS you are adding.
**includes/polling/os/ciscowlc.inc.php**
This file will usually set the variables for $version and $hardware gained from an snmp lookup.
**html/images/os/$os.png**
This is a 32x32 png format image of the OS you are adding support for.

#### <a name="faq9"> What can I do to help?</a>

Thanks for asking, sometimes it's not quite so obvious and everyone can contribute something different. So here are some ways you can help LibreNMS improve.

- Code. This is a big thing. We want this community to grow by the software developing and evolving to cater for users needs. The biggest area that people can help make this happen is by providing code support. This doesn't necessarily mean contributing code for discovering a new device:
    - Web UI, a new look and feel has been adopted but we are not finished by any stretch of the imagination. Make suggestions, find and fix bugs, update the design / layout.
    - Poller / Discovery code. Improving it (we think a lot can be done to speed things up), adding new device support and updating old ones.
    - The LibreNMS main website, this is hosted on Git Hub like the main repo and we accept use contributions here as well :)
- Hardware. We don't physically need it but if we are to add device support, it's made a whole lot easier with access to the kit via snmp.
    - If you've got mibs, they are handy as well :)
    - If you know the vendor and can get permission to use logos that's also great.
- Bugs. Found one? We want to know about it. Most bugs are fixed after being spotted and reported by someone, I'd love to say we are amazing developers and will fix all bugs before you spot them but that's just not true.
- Feature requests. Can't code / won't code. No worries, chuck a feature request into Git Hub with enough detail and someone will take a look. A lot of the time this might be what interests someone, they need the same feature or they just have time. Please be patient, everyone who contributes does so in their own time.
- Be nice, this is the foundation of this project. We expect everyone to be nice. People will fall out, people will disagree but please do it so in a respectable way.
- Ask questions. Sometimes just by asking questions you prompt deeper conversations that can lead us to somewhere amazing so please never be afraid to ask a question.
