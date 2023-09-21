<?php

namespace Civi\CampaignManager\Utils;

class ClassScanner {

  /**
   * Scan all directories to discover specific classes
   */
  public static function scanClasses($namespace, $extends = NULL, $cache_key = 'classes') {
    $classes = \Civi::cache('long')->get('campaignmanager.' . $cache_key, []);

    if (!$classes) {
      foreach (self::getAllClasses($namespace, $extends) as $class) {
        $className = str_replace(\CRM_Utils_File::addTrailingSlash($namespace, '\\'), "", $class);
        //$classes[$className] = $class::getName();
        $classes[$class::getName()] = $className;
      }
      \Civi::cache('long')->set('campaignmanager.' . $cache_key, $classes);
    }

    return $classes;
  }

  /**
   * @return array
   */
  private static function getAllClasses($namespace, $extends = NULL) {
    $classNames = [];
    $locations = array_merge([\Civi::paths()->getPath('[civicrm.root]/Civi.php')],
      array_column(\CRM_Extension_System::singleton()->getMapper()->getActiveModuleFiles(), 'filePath')
    );
    foreach ($locations as $location) {
      $nsFolder = str_replace('\\', '/', $namespace);
      $dir = \CRM_Utils_File::addTrailingSlash(dirname($location)) . $nsFolder;
      if (is_dir($dir)) {
        foreach (glob("$dir/*.php") as $file) {
          $className = $namespace . '\\' . basename($file, '.php');
          if (!empty($extends)) {
            if (is_subclass_of($className, $extends, TRUE)) {
              $classNames[] = $className;
            }
          }
          else {
            $classNames[] = $className;
          }
        }
      }
    }
    return $classNames;
  }

}
