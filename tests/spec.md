🛠 Specification Document: Comment Form Login Replacement for Specific Posts
Project: Custom Functionality for WordPress Theme
Feature Name: Comment Form Login Button via ai_style_force_logged_in_user Post Meta
Author: John Dee
Date: 2025-08-05
Status: Draft

✨ Purpose
This functionality ensures that certain posts require the user to be logged in before commenting. It uses post metadata to control whether a specific post should replace the comment form with a login button for non-logged-in users. Non-logged-in users can still view the page content, but the comment form is replaced with a login button that redirects them to the WordPress login page.

🧩 Implementation Approach
Hook Used: comment_form (or template rendering)
The comment form is conditionally replaced with a login button during template rendering, allowing non-logged-in users to view the page content while providing a clear path to authentication for commenting.

🧪 Conditions for Activation
The logic should run only if the following conditions are all true:

The current request is for a singular page (i.e., a single post, page, or custom post type).

The post has a post meta key ai_style_force_logged_in_user.

The value of this meta key is 1 (string) or truthy boolean.

The current user is not logged in.

🔁 Behavior
If the above conditions are met:

The comment form is replaced with a login button that says "Login".

The login button links to the WordPress login page with the redirect_to parameter set to the current post's permalink.

Non-logged-in users can still view the page content, including the post and existing comments.

Bots and crawlers can access the page content without being redirected.

🧱 Technical Details
Post Meta Key: ai_style_force_logged_in_user

Login Button URL: wp_login_url( get_permalink() )

Check for Logged-In: is_user_logged_in()

Check for Singular: is_singular()

Get Meta: get_post_meta( get_the_ID(), 'ai_style_force_logged_in_user', true )


🧪 Testing Scenarios
Scenario	Is Singular?	Meta Exists & Value	Is User Logged In?	Expected Result
A	Yes	1	No	Show post with login button instead of comment form
B	Yes	1	Yes	Show post with normal comment form
C	Yes	Not set	No	Show post with normal comment form
D	No	1	No	Show page normally (no comment form affected)

📝 Notes
This feature allows non-logged-in users to view page content while encouraging login for commenting, improving SEO and bot accessibility.

The login button maintains the same redirect functionality as the previous implementation, returning users to the post after login.

Admins can set the ai_style_force_logged_in_user meta programmatically or via a custom metabox or plugin.

This approach is more user-friendly than full page redirects and maintains better search engine optimization.


