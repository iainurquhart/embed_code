# Embed Code celltype for ExpressionEngine 2.0 Grid Field

### Overview

Embed Code gives your publishers an embed code per Grid row, primarily for creating shortcodes.

![Field Output](http://f.cl.ly/items/4142100Q0N452n0q322x/Image%202014-09-01%20at%2010.39.49%20pm.png)

Cell Settings allow you to use any prefix you like, the row count of each Grid is simply appended to your prefix.

![Field Settings](http://f.cl.ly/items/0z3g0H2o3V1W0b2l2L3c/Image%202014-09-01%20at%2010.41.22%20pm.png)

### Usage

Embedding dynamic content via shortcodes is not new in ExpressionEngine, we've been doing it with Matrix and Copee Pastee for years. This celltype is Grid specific, and the following is how I set things up.

The cell does not store any data, so outputting the cell contents is pointless. 

The basic principle is you want your client to upload an image, and not worry about any html, image resizing and styling.

Essentialy any placement of {image:x} will be replaced with the following markup:

	<figure class="content-image align-right">
		<img src="/images/foo.jpg" alt="Test Image">
		<figcaption>Test Image</figcaption>
	</figure>

To achieve this in EE templates:

1. Loop through grid rows and create EE snippets with each grid row, for example {image:1}, {image:2} etc.
2. You replace any instances of the created snippets with the values/markup you define, template side.

### Grid configuration

Create a grid field with the short name of `content_images`. Add 4 cells which will contain the embed code, layout options, the image upload/select, and an optional caption.

![Field Settings](http://f.cl.ly/items/1F033L1s0L1k0z1I3m25/Image%202014-09-02%20at%2012.26.35%20pm.png]

Go ahead and publish an entry with multiple images in your Grid field, copy the embed codes into your main content field which will contain the final rendered images.

You should have something like this:

![Field Settings](http://f.cl.ly/items/0E0M020P160m0m1o2d0G/Image%202014-09-02%20at%2012.31.32%20pm.png]

### Output to templates

For creating the embed Snippets to be parsed by EE, I use two add-ons: Stash, and Low Replace.

Stash allows you to create snippets on the fly with any template markup you want. We want to create snippets such as `{image:1}` that will magically transform to become our `<figure>` markup above.

So inside our channel entries tag, we loop the grid field and create those snippets:

	{content_images} {!-- Grid loop --}
		{exp:stash:set name="image:{content_images:count}" type="snippet"}
			<figure class="content_image {content_images:alignment}">
				<img src="{content_images:image:800w}" alt="{content_images:caption}" />
				{if content_images:caption}<figcaption>{content_images:caption}</figcaption>{/if}
			</figure>
		{/exp:stash:set}
	{/content_images}

Note also I'm using an image manipulation `800w` to output a resized image as defined in my upload settings in EE. The name parameter on the stash:set tag is set with the same value as the embed code copied from the Grid field, which is the row count `name="image:{content_images:count}"` (resulting in `name="image:1"` for example). The contents of the stash:set tag pair will become the replacement value.

Now that we have our snippets set, we've got a little problem with the embed codes in our WYSIWYG - you'll find that each embed code is wrapped in `<p>` tags. We don't want that so will need to strip them out, this is where Low Replace comes in handy. We run a regex find/replace on the main content field to strip out those unwanted `<p>` tags.

Heres an example to remove those from my main_content field

	{exp:low_replace find="<p.*?>.*?({.*?:[0-9]+}).*?<\/p>" replace="$1" regex="yes"}
		{main_content}
	{/exp:low_replace}

So tying all that together, here's an example 'page' builder template:


	{embed="_layouts/_master"}
	{exp:channel:entries channel="foo"}

		{content_images}
			{exp:stash:set name="image:{content_images:count}" type="snippet"}
				<figure class="content_image {content_images:alignment}">
					<img src="{content_images:image:800w}" alt="{content_images:caption}" />
					{if content_images:caption}<figcaption>{content_images:caption}</figcaption>{/if}
				</figure>
			{/exp:stash:set}
		{/content_images}

		{exp:stash:set name="main_content"}
			<h1>{title}</h1>
			{exp:low_replace find="<p.*?>.*?({.*?:[0-9]+}).*?<\/p>" replace="$1" regex="yes"}
				{main_content}
			{/exp:low_replace}
		{/exp:stash:set}

	{/exp:channel:entries}

Now on our `/_layouts/_master` template, we can use stash to parse the snippets, and voila:

	<html>
	<head>
		...
	</head>
	<body>
		...
		{exp:stash:get name="main_content" parse_tags="yes"}
		..
	</body>
	</html>

Our dynamic snippets should all be inserted into our main content field.

Enjoy!

* * *

Copyright (c) 2014 Iain Urquhart
http://iain.co.nz

