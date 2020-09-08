---
type:               "post"
title:              "How to manage translations for your object using SonataAdminBundle"
date:               "2012-07-27"
publishdate:        "2012-07-27"
draft:              false
slug:               "how-to-manage-translations-for-your-object-using-sonataadminbundle"
description:        "How to manage translations for your object using SonataAdminBundle"

thumbnail:          "/images/posts/thumbnails/rocket.jpg"
tags:               ["Bundle","Doctrine","Symfony","Translations"]
categories:         ["Dev", Symfony", "PHP"]

author_username:    "tbessoussa"
---


Many of us asked themselves **how to add dynamically translations to I18n fields  - object using `SonataAdminBundle` and `DoctrineExtensions`**.
</p>

Thanks to <a href="http://gediminasm.org/" target="_blank">Gedmo</a> and his wonderful <a href="https://github.com/l3pp4rd/DoctrineExtensions" target="_blank">DoctrineExtensions</a> on which he added a feature called "Personal Translations" that simplifies the whole translation management process.

**Before starting here's what i am using :**

*   <a href="https://github.com/symfony/symfony" target="_blank">Symfony 2.1-DEV</a>
*   Doctrine & Doctrine deps (master branch)
*   <a href="https://github.com/l3pp4rd/DoctrineExtensions" target="_blank">DoctrineExtensions</a> ( >= 2.3 tag)
*   <a href="https://github.com/stof/StofDoctrineExtensionsBundle" target="_blank">StofDoctrineExtensionsBundle</a> (master branch)
*   [TranslationFormBundle][1]

We will not configure the *DoctrineExtensions* & the *StofDoctrineExtensionsBundle* together, so please, check that it's all configured (read the readme on github repos) and working.

**Before starting, let's configure the Bundle :**

{{< highlight yaml >}}

# Add the bundle to your composer

    "a2lix/translation-form-bundle" : "dev-master"

# Enable the Bundle in the AppKernel.php

    new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),


# Configure the Bundle in the config.yml

    a2lix_translation_form:
        default_locale: en                  # [Optionnal] Default to 'en'
        locales: [fr, es, de]               # [Optionnal] Array of translations locales. Can be specified in the form.
        default_required: false             # [Optionnal] Default to false. In this case, translation fields are not mark as required with html5

# Template
    twig:
        form:
            resources:
                - 'A2lixTranslationFormBundle::form.html.twig'
{{< /highlight >}}


**Let's begin with our `Category` entity :**

{{< highlight php >}}

<?php

namespace AwesomeNamespace\AwesomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * AwesomeNamespace\AwesomeBundle\Entity\Profile
 *
 * @ORM\Table(name="profile")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="AwesomeNamespace\AwesomeBundle\Entity\ProfileTranslation")
 */
class Profile
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var text $description
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="ProfileTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * Required for Translatable behaviour
     * @Gedmo\Locale
     */
    protected $locale;

    public function __construct()
    {
        $this->translations = new ArrayCollection;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(ProfileTranslation $t)
    {
        $this->translations->add($t);
        $t->setObject($this);
    }

    public function removeTranslation(ProfileTranslation $t)
    {
        $this->translations->removeElement($t);
    }

    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }

    public function __toString()
    {
        return $this->getName();
    }

}
{{< /highlight >}}


**And our `CategoryTranslation` entity (to store translations)**

{{< highlight php >}}

<?php

namespace AwesomeNamespace\AwesomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="profile_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class ProfileTranslation extends AbstractPersonalTranslation
{
    /**
     * Convinient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $content
     */
    public function __construct($locale = null, $field = null, $content = null)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($content);
    }

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
{{< /highlight >}}


**Finally, the `CategoryAdmin` class**

{{< highlight php >}}

<?php

namespace AwesomeNamespace\AwesomeBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * Profile Admin
 */
class ProfileAdmin extends Admin
{

    /**
     * Configure the list
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('name', null, array('label' => 'Name'))
            ->add('description', null, array('label' => 'Description'));
    }

    /**
     * Configure the form
     *
     * @param FormMapper $formMapper formMapper
     */
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'a2lix_translations', array(
                'by_reference' => false,
                'locales' => array('fr', 'en'))
        );
    }

}
{{< /highlight >}}


You wanna see the result ? Well, you are free to propose a gist in the comment section of this article if you want to add code to render more nicely the translations in `SonataAdminBundle` (display the locale of the field, split the fields in tab....)

**EDIT **The Bundle has been updated and now has a splitted view and tabbed view per locale.

<div style="text-align:center;">
{{< figure src="/images/posts/2012/translations.png"  alt="translations 285x300 How to manage translations for your object using SonataAdminBundle" width="285" height="300">}}
</div>

**So what does the bundle <a href="https://github.com/a2lix/TranslationFormBundle" target="_blank">TranslationFormBundle</a> ?**

It'll grab all the fields tagged with the *Translatable via its listener*, then it'll read the properties annotations in order to add in your form all the fields with the right type (string / text). You have to specify an array of locales

So Have Fun translating your objects into the AdminBundle, and we can say thank you to <a href="https://github.com/a2lix" target="_blank">a2lix</a> who made this usefull, yet uknown Bundle.

You can find another example, on how to Translate your object in a form, (but outside the SonataAdminBundle) on the owner repo [here][2].

 [1]: https://github.com/a2lix/TranslationFormBundle
 [2]: https://github.com/a2lix/DemoTranslationBundle
