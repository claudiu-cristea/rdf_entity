<?php

declare(strict_types = 1);

namespace Drupal\rdf_entity;

/**
 * Provides a contract for the RDF entity field handler service.
 */
interface RdfFieldHandlerInterface {

  /**
   * Defines the resource type.
   *
   * @var string
   */
  const RESOURCE = 'resource';

  /**
   * Defines the translatable literal type.
   *
   * @var string
   */
  const TRANSLATABLE_LITERAL = 't_literal';

  /**
   * Defines the literal type.
   *
   * @var string
   */
  const NON_TYPE = 'literal';

  /**
   * Returns the SPARQL-to-Drupal mapping array.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   *
   * @return array
   *   The SPARQL-to-Drupal mapping array.
   */
  public function getInboundMap(string $entity_type_id): array;

  /**
   * Returns the predicates for a given field.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param string $field_name
   *   The field name.
   * @param string|null $column_name
   *   (optional) The column name. If omitted, the main property will be used.
   * @param string|null $bundle
   *   (optional) If passed, filter the final array by bundle.
   *
   * @return string[]
   *   An array of predicates.
   *
   * @throws \Drupal\rdf_entity\Exception\UnmappedFieldException
   *    Thrown when a unmapped field is requested.
   */
  public function getFieldPredicates(string $entity_type_id, string $field_name, ?string $column_name = NULL, ?string $bundle = NULL): array;

  /**
   * Returns the format for a given field.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param string $field_name
   *   The field name.
   * @param string|null $column_name
   *   (optional) The column name. If omitted, the main property will be used.
   * @param string|null $bundle
   *   (optional) If passed, filter the final array by bundle.
   *
   * @return string[]
   *   An array of predicates.
   *
   * @throws \Exception
   *    Thrown when a non existing field is requested.
   */
  public function getFieldFormat(string $entity_type_id, string $field_name, ?string $column_name = NULL, ?string $bundle = NULL): array;

  /**
   * Returns the field's main property.
   *
   * @param string $entity_type_id
   *   The entity type machine name.
   * @param string $field_name
   *   The field name.
   *
   * @return string
   *   The main property of the field.
   */
  public function getFieldMainProperty(string $entity_type_id, string $field_name): string;

  /**
   * Returns a flat list of property URIs of the given entity type ID.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   *
   * @return string[]
   *   An array of property URIs that belong to the entity type ID.
   */
  public function getPropertyListToArray(string $entity_type_id): array;

  /**
   * Returns if the field has a predicate mapped for the given entity type ID.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param string $bundle
   *   The bundle ID.
   * @param string $field_name
   *   The field name.
   * @param string $column_name
   *   The field column.
   *
   * @return bool
   *   Whether the field is mapped for an entity type ID.
   */
  public function hasFieldPredicate(string $entity_type_id, string $bundle, string $field_name, string $column_name): bool;

  /**
   * Converts a list of bundle IDs to their corresponding URIs.
   *
   * @param string $entity_type_id
   *   The entity type id.
   * @param string[] $bundles
   *   An array of bundle machine names.
   * @param bool $to_resource_uris
   *   (optional) If true, the IDs will be transformed into resource IDs
   *   instead. Defaults to FALSE.
   *
   * @return string[]
   *   The altered array.
   *
   * @throws \Exception
   *    Thrown when the bundle does not have a mapping.
   */
  public function bundlesToUris(string $entity_type_id, array $bundles, bool $to_resource_uris = FALSE): array;

  /**
   * Returns the outbound value for the given field.
   *
   * This method will be used to convert the value to it's respective SPARQL
   * format e.g. integer value '1' will be converted to '1^^<xsd:integer>'.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param string $field_name
   *   The field name.
   * @param mixed $value
   *   The value to convert.
   * @param string|null $langcode
   *   (optional) Pass the language code if one exists. This should be NULL if
   *   the format is not 't_literal'.
   * @param string|null $column_name
   *   The column for which to calculate the value. If null, the field's main
   *   column will be used.
   * @param string|null $bundle
   *   (optional) The same field of an entity type might use different value
   *   formats, depending on how is mapped on each bundle. Pass the bundle, when
   *   is available, for a better determination of the value format.
   *
   * @return mixed
   *   The calculated value.
   */
  public function getOutboundValue(string $entity_type_id, string $field_name, $value, ?string $langcode = NULL, ?string $column_name = NULL, ?string $bundle = NULL);

  /**
   * Returns the inbound bundle mapping.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param string $bundle_uri
   *   The bundle URI.
   *
   * @return string[]
   *   An array of bundles that match the requested bundle.
   *
   * @throws \Exception
   *    Thrown when the bundle is not found.
   */
  public function getInboundBundleValue(string $entity_type_id, string $bundle_uri): array;

  /**
   * Returns the inbound value for the given field.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param string $field_name
   *   The field name.
   * @param mixed $value
   *   The value to convert.
   * @param string|null $langcode
   *   (optional) Pass the language code if one exists. This should be NULL if
   *   the format is not 't_literal'.
   * @param string|null $column_name
   *   (optional) The column name for which to calculate the value. If omitted,
   *   the field main property will be used.
   * @param string|null $bundle
   *   (optional) The same field of an entity type might use different value
   *   formats, depending on how is mapped on each bundle. Pass the bundle, when
   *   is available, for a better determination of the value format.
   *
   * @return mixed
   *   The calculated value.
   */
  public function getInboundValue(string $entity_type_id, string $field_name, $value, ?string $langcode = NULL, ?string $column_name = NULL, ?string $bundle = NULL);

  /**
   * Returns an array of available data types.
   *
   * @return \Drupal\Component\Render\MarkupInterface[]
   *   An array of data types.
   */
  public static function getSupportedDataTypes(): array;

}
