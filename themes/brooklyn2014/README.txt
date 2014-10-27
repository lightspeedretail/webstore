README for Brooklyn2014 theme

Thank you for taking the time to read this file.
Below are some very important notes on how to make changes to the look of your web store.

If you have opened this file from the original brooklyn2014 theme, DO NOT, manually modify ANY of the files in this folder.
The Brooklyn2014 theme may get updated automatically and any changes you make will be lost.

To begin designing a custom theme, use the following directions:

1. Go to Admin Panel and choose the Themes menu.
2. Click on "Manage My Themes".
3. Ensure brooklyn2014 is selected and *click the "Copy selected theme for customization" button. This makes a copy of the brooklyn2014 and switches Web Store to use it.

At this point, you can make changes to the files in the newly created brooklyn2014copy folder as you desire.

What files you change depends on what types of changes you're trying to make. If you are simply wanting to adjust some colors,
our highest recommendation is to use the Edit CSS feature under Themes in the Admin Panel. Copy elements from the other theme files
or make new elements and add them to custom.css. The elements in custom.css will take priority.

Your copy of the brooklyn2014 will contain a folder 'views' with files from the theme's core viewset.
For major changes including layout, you can adjust/amend these files.

The system works like "onion layers". If it finds the file in the theme, that's what it uses. If it's missing, it goes back to the equivalent file under the /protected folder. For this reason, you never have to modify a protected file because your theme always takes priority.

A final note, once you are happy with your changes,
1. Navigate to the Admin Panel, choose the Themes menu.
2. Click on "Manage My Themes".
3. Ensure your brooklyn2014copy is selected and *click the "Remove Unchanged Files from selected theme". This will remove any files
that are identical to their parents in the core. Thus, if changes are made to those core files, your theme automatically picks them up as well.

In summary: Never modify anything under /protected/views-cities3 or under /themes/brooklyn2014.
Keep all your customizations under your own theme folder. This will allow you to have a great Web Store that can be continuously updated automatically and you'll never run into conflicts.

------
* Depending on your hosting plan (Lightspeed Hosting, Self Hosting) this option is not available.
