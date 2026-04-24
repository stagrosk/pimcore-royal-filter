<?php

namespace PimcoreHeadlessContentBundle\Helper;

use Cocur\Slugify\Slugify;
use OpenDxp\Model\DataObject;
use OpenDxp\Tool;
use PimcoreHeadlessContentBundle\Model\ContentPageInterface;
use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;

class SlugGenerator
{
    /**
     * @var \Cocur\Slugify\Slugify
     */
    private Slugify $slugify;

    public function __construct()
    {
        $this->slugify = new Slugify([
            'rulesets' => ['default', 'slovak', 'czech', 'german', 'polish', 'hungarian'],
        ]);
    }

    /**
     * @param \PimcoreHeadlessContentBundle\Model\SlugAwareInterface $slugAware
     *
     * @return void
     */
    public function updateSlug(SlugAwareInterface $slugAware): void
    {
        $layoutRootContentPage = DataObject::getById(1)->getProperty('LayoutNavigationRoot');

        $backup = DataObject\AbstractObject::getGetInheritedValues();
        DataObject\AbstractObject::setGetInheritedValues(true);

        foreach (Tool::getValidLanguages() as $language) {
            if ($slugAware instanceof ContentPageInterface && !$slugAware->getParent() instanceof ContentPageInterface) {
                // this is the front page
                $slugAware->setHandle('/', $language);
                $slugAware->setSlug(null, $language);

                continue;
            }

            // NAME from slugValue + fallback
            $name = $slugAware->getSlugValue($language);
            if (empty($name)) {
                foreach (Tool::getFallbackLanguagesFor($language) as $fallback) {
                    $fallbackName = $slugAware->getSlugValue($fallback);
                    if (!empty($fallbackName)) {
                        $name = $fallbackName;
                        break;
                    }
                }
            }

            // is still empty so try to get from name + fallback
            if (empty($name)) {
                $name = $slugAware->getName($language);
                if (empty($name)) {
                    foreach (Tool::getFallbackLanguagesFor($language) as $fallback) {
                        $fallbackName = $slugAware->getName($fallback);
                        if (!empty($fallbackName)) {
                            $name = $fallbackName;
                            break;
                        }
                    }
                }
            }

            if (empty($name)) {
                $name = $language . '-' . $slugAware->getId();
            }

            $shouldUpdateSlug = true;
            if ($slugAware instanceof ContentPageInterface && $layoutRootContentPage instanceof ContentPageInterface) {
                $shouldUpdateSlug = $slugAware->getId() != $layoutRootContentPage->getId();
            }

            if ($shouldUpdateSlug) {
                $slug = $this->slugify->slugify($name);
                $slug = $this->checkIfSlugIsUnique($slugAware, $slug, $language);

                $slugAware->setSlug($slug, $language);
            }
        }

        DataObject\AbstractObject::setGetInheritedValues($backup);
    }

    /**
     * @param \PimcoreHeadlessContentBundle\Model\SlugAwareInterface $initialObject
     */
    public function updateHandle(SlugAwareInterface $initialObject): void
    {
        foreach (Tool::getValidLanguages() as $language) {
            // check slug in language
            if (empty($initialObject->getSlug($language))) {
                continue;
            }

            $slugs = [];
            $object = $initialObject;
            while ($object instanceof SlugAwareInterface) {
                $currentSlug = $object->getSlug($language);
                if (!empty($currentSlug)) {
                    $slugs[] = $currentSlug;
                }

                $object = $object->getParent();
            }

            // skip if translation is empty
            if (empty($slugs)) {
                continue;
            }

            $slugs = array_reverse($slugs);

            $handle = '/' . implode('/', array_values($slugs));
            if ($handle !== $initialObject->getHandle($language)) {
                $initialObject->setHandle($handle, $language);
            }
        }
    }

    /**
     * @param \PimcoreHeadlessContentBundle\Model\SlugAwareInterface $object
     * @param string $slug
     * @param string $language
     *
     * @return string
     */
    private function checkIfSlugIsUnique(SlugAwareInterface $object, string $slug, string $language): string
    {
        // actual slug in language on data object
        $objectSlug = $object->getSlug($language);

        if ($objectSlug) {
            // get max 2 objects by slug from database
            $objects = $object::getBySlug($slug, $language, 2);
            if ($objects instanceof DataObject\Listing) {
                $objects = $objects->getObjects();
            }

            // Check if object has slug with id on the end
            if ($object->getId() == substr($objectSlug, strrpos($objectSlug, '-') + 1)) {
                // there is 1 object with slug, id of saved object is same then founded from DB
                if (count($objects) === 0 || (count($objects) === 1 && reset($objects)->getId() === $object->getId())) {
                    $objectSlug = $slug;
                } else {
                    $objectSlug = $this->addIdToSlug($object, $slug);
                }
            }
            // there is 1 object with slug, id of saved object is same then founded from DB
            elseif (count($objects) === 0 || (count($objects) === 1 && reset($objects)->getId() === $object->getId())) {
                $objectSlug = $slug;
            }
            // must have slug with id
            else {
                $objectSlug = $this->addIdToSlug($object, $slug);
            }
        } else {
            // we already have product with slug -> add id
            $existingObject = $object::getBySlug($slug, $language, 1);
            if (!empty($existingObject)) {
                $objectSlug = $this->addIdToSlug($object, $slug);
            } else {
                $objectSlug = $slug;
            }
        }

        return $objectSlug;
    }

    /**
     * @param \PimcoreHeadlessContentBundle\Model\SlugAwareInterface $object
     * @param string $slug
     *
     * @return string
     */
    private function addIdToSlug(SlugAwareInterface $object, string $slug): string
    {
        return sprintf('%s-%s', $slug, $object->getId());
    }
}
