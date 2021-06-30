# wp-graphql-wp-shopify
A WP GraphQL extension that adds support for WP Shopify

Registers post types `wps_product` as Product and `wps_collection` as Collection in WP GraphQL.

Adds `shopifyProductId` field to product and `shopifyCollectionId` to collection.

example product query:

```
{
  product {
    shopifyProductId
  }
}
```
example collection query:
```
{
  collection {
    shopifyCollectionId
  }
}
```


## Using with [gatsby-source-shopify](https://github.com/gatsbyjs/gatsby-source-shopify) and [gatsby-source-wordpress](https://www.gatsbyjs.com/plugins/gatsby-source-wordpress/)

Using [Gatsby's schema customization API](https://www.gatsbyjs.com/docs/reference/graphql-data-layer/schema-customization/)

```
exports.createSchemaCustomization = ({ actions, schema }) => {
  // links the ShopifyProducts from the `gatsby-source-shopify`
  // to the wpProduct { product } field in `gatsby-source-wordpress`
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
