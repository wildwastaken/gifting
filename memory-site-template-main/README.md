# Basics of Kirby CMS in PHP
Today, we’re going to:

1. Get Kirby CMS running locally
2. Once it’s working, poke around in the memory site template and learning how it works

We’ll do more things with Kirby and PHP in the following weeks.


----------
# Part 1 — Getting Kirby CMS running locally

Mission:
Get a local version of Kirby CMS (starterkit) running locally on your computer.

Strategy:
To do this, you'll locally run a PHP server.


## Option A — Use MAMP or similar software

One way to do this is through downloading the program [MAMP](https://www.mamp.info/en/mac/). (There are similar programs like [Herd](https://herd.laravel.com/).)

Then, download the "memory-site-template-main" .zip of Kirby from https://github.com/oilstel/memory-site-template. Find the “Code” button in the upper right and click “Download .zip.” Unzip it and put it inside of your MAMP's website folder. (On Mac this is located at `Applications/MAMP/htdocs`. You can find/edit this path in the Preferences/Server part of MAMP program.)

Put the folder `memory-site-template``-main` folder inside `htdocs` folder. When you boot your server, go to http://localhost:8888, and you should see the folder `memory-site-template``-main` as a link. If you click on it and you see a fake magazine website, with the URL http://localhost:8888/memory-site-template-main, it works!

Also, test that you can go to a subpage by clicking on one of the colored block posts. If you can get to that subpage (it doesn’t show “not found”) then you’re in good shape. You should also be able to add `/panel` in the URL (http://localhost:8888/memory-site-template-main/panel), and it goes to a nice login screen.

*

If for some reason the above isn’t working, you can try the fix below:

First, Open this file in your code editor: `/Applications/MAMP/conf/apache/httpd.conf`

Uncomment (which means remove the # symbol) this line, which is around line 179 of the file:


    #LoadModule rewrite_module modules/mod_rewrite.so

Once you remove the #, save the `httpd.conf` file.

Then, restart your MAMP server

Go to `/panel` or http://localhost:8888/memory-site-template-main/panel, and the login screen should appear.


## Option B — Boot your own PHP server

You’ll use terminal with command line for this. 

First, you need to install PHP —

I’ll share a way for Mac users below. If you’re not a Mac user (but have a Windows or Linux computer), follow [this guide](https://medium.com/novai-php-laravel-101/how-to-install-php-command-line-on-macos-linux-and-windows-e39c5adab724) instead.

If you’re a Mac user, first install [Homebrew](https://brew.sh/) if you don’t have it installed.

Run this line in your terminal:


    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

Follow the prompts and press ENTER a few times when directed until Homebrew is completely installed.

Then, install PHP with this command:

`brew install php`

Wait until PHP installs. Once it does…

Download the "memory-site-template-main" .zip of Kirby from https://github.com/oilstel/memory-site-template. Find the “Code” button in the upper right and click “Download .zip.” Unzip it and put it in your “Sites” (or wherever you put your sites for this class).

In terminal, navigate to this folder by typing:

`cd`

It stands for “change directory”

Then either drag your `memory-site-template-main` into your terminal to copy the path, or type in the path of this folder. It will look like:

`cd /Users/your-username/Sites/memory-site-template-main`

Then press ENTER or RETURN. Now you should be in that directory.

Once you’re there (you can run the command `pwd`, or “print working directory” to confirm you’re there), run this:

`php -S localhost:4000 kirby/router.php`

Once you do, you should be able to navigate in your web browser to http://localhost:4000 and see your Kirby site.

Try going to a subpage by clicking on one of the colored block posts. If you can get to that post page (it doesn’t show “not found”) then you’re in good shape. You should also be able to add `/panel` in the URL and get to a login page.


----------
# Part 2 — Logging into Kirby & Editing

Navigate to `/panel` on your local version of Kirby. You should get something like this:


![](https://paper-attachments.dropboxusercontent.com/s_7F2D1DA9C2ADBA740B2A13E623911A1F550F8C796DC57EB5E466CA0E6C76BACA_1742902055956_Screenshot+2025-03-25+at+12.26.47.png)


Create a login and password (can be anything) and press “Install.”

Then you’ll arrive at the “panel” or the backend. It should look something like this:


![](https://paper-attachments.dropboxusercontent.com/s_7F2D1DA9C2ADBA740B2A13E623911A1F550F8C796DC57EB5E466CA0E6C76BACA_1742902192427_Screenshot+2025-03-25+at+12.28.49.png)


(If for some reason you have errors on the homepage of the actual website, you might need to press in the right hand column “Generate a static version of this site” to get them to clear.)

Feel free to navigate around this backend and explore the various posts…


----------

Reminder:

Backend is the CMS (content management system), and the frontend is what results from that:

**Backend:** http://localhost:4000/memory-site-template-main/panel ← /Panel
**Frontend:** http://localhost:4000/memory-site-template-main/


----------

For example, this is what the “Public” posts look like:


![](https://paper-attachments.dropboxusercontent.com/s_7F2D1DA9C2ADBA740B2A13E623911A1F550F8C796DC57EB5E466CA0E6C76BACA_1742902362665_Screenshot+2025-03-25+at+12.32.25.png)

Try going into one…

![](https://paper-attachments.dropboxusercontent.com/s_7F2D1DA9C2ADBA740B2A13E623911A1F550F8C796DC57EB5E466CA0E6C76BACA_1742903555654_Screenshot+2025-03-25+at+12.52.19.png)

… changing the content of the post, saving (in upper right hand corner, pressing “Save”). 

Below, I’m adding “This is a test… testing 123 🙂” to the bottom.

![](https://paper-attachments.dropboxusercontent.com/s_7F2D1DA9C2ADBA740B2A13E623911A1F550F8C796DC57EB5E466CA0E6C76BACA_1742903691250_Screenshot+2025-03-25+at+12.54.16.png)

Then press the “right up” arrow in the upper right hand corner to see the updated frontend:

![](https://paper-attachments.dropboxusercontent.com/s_7F2D1DA9C2ADBA740B2A13E623911A1F550F8C796DC57EB5E466CA0E6C76BACA_1742903749347_Screenshot+2025-03-25+at+12.54.31.png)


# Part 3 — Editing the templates

We’ll do this next week!

---
This template was created for Laurel Schwulst's class at Princeton on March 25, 2025