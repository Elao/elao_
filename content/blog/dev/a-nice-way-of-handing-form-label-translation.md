
---
type:               "post"
title:              "A nice way of handling form label translation"
date:               "2013-09-13"
lastModified:       ~
lang:               "en"

description:        "A nice way of handling form label translation"

thumbnail:          "images/posts/thumbnails/cool_cat.jpg"
tags:               ["Symfony", "Form", "Theming", "Translation"]
categories:         ["Dev", "Form", "Symfony"]
author:             "tjarrand"
---

## The problem

When you need to translate the labels of the different forms in your app, you usually have to set a translation key for each label manually.<!--more-->

# **[EDIT]** We finally built a bundle that solve the problem in an even better way and more! <a href="http://github.com/Elao/ElaoFormTranslationBundle" title="Check it out" target="_blank">Check it out</a>

**Here's what it looks like with a simple User form:**

```php
<?php

namespace Elao\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Elao\UserBundle\Form\Type\GroupType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, ['label' => 'register.labels.firstname'])
            ->add('lastname', null, ['label' => 'register.labels.lastname'])
            ->add('email', null, ['label' => 'register.labels.email'])
            ->add('phone', null, ['label' => 'register.labels.phone'])
            ->add(
                'password',
                'repeated',
                [
                    'type'            => 'password',
                    'first_options'   => ['label' => 'register.labels.password.first'],
                    'second_options'  => ['label' => 'register.labels.password.second'],
                ]
            )
            ->add(
                'group',
                new GroupType,
                [
                    'label' => 'register.labels.team.label'
                ]
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Elao\UserBundle\Entity\User',
            ]
        );
    }

    public function getName()
    {
        return 'register';
    }
}
```

```php
<?php

namespace Elao\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, ['label' => 'register.labels.team.name']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Elao\UserBundle\Entity\Group',
            ]
        );
    }

    public function getName()
    {
        return 'group';
    }
}
```

```yaml
register:
    labels:
        firstname:      Your firstname
        lastname:       Your lastname
        email:          Your email address
        phone:          Your Phone number
        password:
            first:      Choose your password
            second:     Confirm your password
        group:
            label:      Your Team
            name:       Name
```


We have some simple fields, a repeated password and an embedded Group form.

**Problems:** it’s kinda boring, you can easily make typos in the keys, every change you’ll make will involve translations files and the form classes, ... It can become quite painful with the number of forms in you app increasing.

*We'd rather have the label translation keys automatically generated, don't we?*

**Here's how we do it:**

## Remove all label translation keys from the forms:

```php

<?php

namespace Elao\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Elao\UserBundle\Form\Type\GroupType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('password', 'repeated', ['type' => 'password'])
            ->add('group', new GroupType);
    }

    // ...
}
```

```php

<?php

namespace Elao\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }

    // ...
}
```


**Better right ?**

## Construct the label translation keys automatically with form theming:

```twig

{% extends "form_div_layout.html.twig" %}

{% block form_label %}
{% spaceless %}

    {% import _self as macros %}

    {% if label is empty %}
        {% set label = macros.form_parent_name(form) %}
    {% endif %}

    {{ parent() }}

{% endspaceless %}
{% endblock form_label %}

{% macro form_parent_name(form, prefix) %}
{% spaceless %}

    {% import _self as macros %}

    {% set prefix = prefix|default(false) %}

    {% if form.parent is empty %}
        {{ form.vars.name }}.labels
    {% else %}
        {% if form.vars.compound and not prefix %}
            {{ macros.form_parent_name(form.parent) }}.{{ form.vars.name }}.label
        {% else %}
            {{ macros.form_parent_name(form.parent, true) }}.{{ form.vars.name }}
        {% endif %}
    {% endif %}

{% endspaceless %}
{% endmacro %}
```


What we do here:

*   Extend the `*form\_div\_layout.html*`.
*   Override the default `**form_label**` to set the `**label**` variable (if not provided) before rendering the default template with *parent()*
*   Use the recursive macro `**form\_parent\_name**` to construct the key this way: *[parent_form].labels.[child]*

**Note:**
We handle the special issue of embedded forms with the `prefix` variable in the macro.
When we construct the label of an embedded form, we add `.label` at the end of the key.
When the children of this form want to get the parent key, we just return the prefix key (without the `.label` addition).
*This has to be done because in YML we can't set a value the key `register.labels.group` and stil set children keys such as `register.labels.group.name`. We need the first one to be `register.labels.group.label`.*

## Conclusion:

We are now generating exactly the key we need, automatically:

```
register.labels.firstname
register.labels.lastname
register.labels.email
register.labels.phone
register.labels.password.first
register.labels.password.second
register.labels.group.label
register.labels.group.name
```

- All your label are now rendered with a generated translation key that **logically follows your forms structure**.

- It's **non-intrusive**: the key is only generated if a label is not provided. Meaning you can still set a custom label translation key for a field in you form classes.

- The logic behind key generation can be **customized** by coding your own macro.
