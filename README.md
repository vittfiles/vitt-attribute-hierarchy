<!-- Please update value in the {}  -->

<h1 align="center">Vitt Attribute Hierarchy for WooCommerce</h1>

<div align="center">
  <h3>
    <a href="https://wordpress.org/plugins/vitt-attribute-hierarchy-for-woocommerce/">
      Plugin for wordpress
    </a>
  </h3>
</div>

<!-- TABLE OF CONTENTS -->

## Table of Contents

- [Overview](#overview)
- [how to use](#how-to-use)
- [Contact](#contact)

<!-- - [Features](#features) -->
<!-- OVERVIEW -->

## Overview

This plugin works simulating a hierarchy for attributes and subattributes.

The plugin looks up the sub-attributes in your product, then for each value it looks up its parent and adds the parent attribute and the parent value in the product.
When using a filter with the main attribute on the store page, all the products that have that main attribute are returned, which are the same products that contain the associated sub-attributes.

Important:
You can't create multiple hierarchy levels, just one.
Two diferent attributes cannot contain the same sub-attribute.

## how to use
1. Create the attribute(father) and sub-attribute( child ) with the diferents values.
   Example: attribute ( color-base ) - sub-attribute ( color )
   <img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example01.webp" alt="create attribute and sub-attribute" style="max-width:700px;"/>


2. Go to "Vitt Attribute" in menu to create a new hierarchy(connection):
    <img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example02.webp" alt="plugin page" style="max-width:700px;"/>


3. Add the attribute slug and sub-attribute slug and press "Add connection".
    <img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example03.webp" alt="create attributes connection" style="max-width:700px;"/>

4. Now you have a new hierarchy created and can press "Configure terms" to define which attribute value is "parent" of the      sub-attribute value:
    <img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example04.webp" alt="show connection between two attributes" style="max-width:700px;"/>

5. The list object with Base are the "fathers" and the others are children.
    Drag and drop the attribute value from the list, all the childrens bellow a Base are childrens from these attribute value.
    At the top you have the sub-attribute values that don't have a father.
    <img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example05.webp" alt="list of values to connect" style="max-width:700px;"/>
    

6. Example: Cherry is a sub-value of value Red and White is a sub-value of value White.
    <img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example06.webp" alt="hierarchy example" style="max-width:700px;"/>

7. Then press "update" to update the changes in the database and products. If you have a lot of products it can be slow.
    <img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example07.webp" alt="update connections" style="max-width:700px;"/>

8. Now you can add the sub-attribute values in your product page.Then save the attribute and after that update or save the product.
    <img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example08.webp" alt="adding the cherry color in the product page" style="max-width:700px;"/>

9. After update or save the product you can see the "father" attribute value. In this case the attribute value is Red, the father of Cherry.
    <img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example09.webp" alt="The color base attribute and red value were created" style="max-width:700px;"/>

10. Finally you can use the "father" attribute in filters. In this case if you select color base  Red, the filter show you the product with cherry color.
    <img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example10.webp" alt="Red filter disabled" style="max-width:700px;"/><img src="https://github.com/vittfiles/vitt-attribute-hierarchy/blob/main/example-img/example11.webp" alt="Red filter enabled" style="max-width:700px;"/>
## Contact

- GitHub [vittfiles](https://github.com/vittfiles)