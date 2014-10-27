README for Brooklyn theme

Thank you for taking the time to read this file. Below are some very important notes on how to make changes to the look of your web store.

First, DO NOT, under any circumstances, manually modify any of the files in this folder. You will notice another folder called "custom". On a brand new installation, custom is simply a copy of Brooklyn, and we include it as the best place to make a custom theme if you desire.

The simple reason for this is that the Brooklyn theme may get updated automatically and any changes you make will be lost.

To begin designing a custom theme, use the following directions:

1. Go to Admin Panel and choose the Themes menu. Under "Manage My Themes", click the "Custom" theme and Apply. This switches Web Store to use it instead of Brooklyn (although at the beginning it's a copy so it will look identical).
2. At this point, you can make changes to the themes/custom folder as you desire.

What files you change depends on what types of changes you're trying to make. If you are simply wanting to adjust some colors, our highest recommendation is to use the included /themes/custom/css/custom.css file. You'll notice it's blank. Depending on what you want to start with, copy the contents of either light.css or dark.css to your custom.css. This gives you a great starting point. In Admin Panel under themes, change the color dropdown below your Custom theme to "custom" (the dropdown references what css file to load).

Now you can make changes to the CSS coloring as you require. You can also use it to override anything in style.css since it's loaded after that file. Don't modify style.css, just copy or make new elements in custom.css since those will take priority.


For major changes including layout, you can copy files from the viewset to your theme. Viewset files can be found in /protected/views-cities and these are the HTML/PHP layout files that make up the look of Web Store. Again, DO NOT modify anything under views-cities, at any time, ever. This will break automatic upgrades. If you want to customize any file, copy it to your themes folder in the same folder structure and change your copy.

For example, to customize the customer receipt template, which can be found in /protected/views-cities/mail/_customerreceipt.php, you would copy this file to /themes/custom/views/mail/_customerreceipt.php

The system works like "onion layers". If it finds the file in the theme, that's what it uses. If it's missing, it goes back to the equivalent file under the /protected folder. For this reason, you never have to modify a protected file because your theme always takes priority.

One final note, you don't have to copy every view file to your theme because of the way it works. Only copy what you really want to change. This allows updates to work properly in the future.

In summary: Never modify anything under /protected/views-cities or under /themes/brooklyn. Keep all your customizations under your own theme folder. This will allow you to have a great Web Store that can be continuously updated automatically and you'll never run into conflicts.