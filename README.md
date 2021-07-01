# wp-graphql-wp-shopify
A WordPress GraphQL extension that adds support for [WP Shopify](https://wordpress.org/plugins/wpshopify/)

Registers post types `wps_product` as Product and `wps_collection` as Collection in WP GraphQL.

Adds `shopifyProductId` field to product and `shopifyCollectionId` to collection.

example product query:

```javascript
{
  product {
    shopifyProductId
  }
}
```
example collection query:
```javascript
{
  collection {
    shopifyCollectionId
  }
}
```


## Using with [gatsby-source-shopify](https://github.com/gatsbyjs/gatsby-source-shopify) and [gatsby-source-wordpress](https://www.gatsbyjs.com/plugins/gatsby-source-wordpress/)

Using [Gatsby's schema customization API](https://www.gatsbyjs.com/docs/reference/graphql-data-layer/schema-customization/) the following example is using the @link directive to create foreign-key relations between the Shopify and WP Shopify nodes.



```javascript
// gatsby-node.js

// links the ShopifyProducts from the `gatsby-source-shopify`
// to the wpProduct { product } field in `gatsby-source-wordpress`

exports.createSchemaCustomization = ({ actions, schema }) => {
  const { createTypes } = actions
  
  const typeDefs = [
    /* GraphQL */ `
      type WpProduct {
        product: ShopifyProduct @link(by: "shopifyId", from: "shopifyProductId")
      }

      type WpCollection {
        collection: ShopifyCollection
          @link(by: "shopifyId", from: "shopifyCollectionId")
      }

      type ShopifyCollection implements Node {
        wpCollection: WpCollection
          @link(by: "shopifyCollectionId", from: "shopifyId")
      }

      type ShopifyProduct implements Node {
        wpProduct: WpProduct @link(by: "shopifyProductId", from: "shopifyId") # back-link to the WpProduct from the ShopifyProduct
      }
    `
  ]
  
  createTypes(typeDefs)
}
```
