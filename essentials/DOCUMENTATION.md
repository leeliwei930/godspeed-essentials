## Plugin Components

### AllProducer Component

The all producer component render a list of producers,
information such as categories can be eager  loaded by specify include_categories properties with the value of 1.
Number of producers record can be limited by specific limit properties, if value specified
as 0 which indicate there is no limit to display all the producers.



#### Component Usage
```twig
[AllProducer]
include_categories = 1
limit = 4
==

{% for producer in producers %}
    <p>{{ producer.name }}</p>
{% end %}
```


### Events Component
The events component render a list of events. If user is logged in, the component will
display the events based on the user group and any events that doesn't attach with
any user_group.

#### Component page variables

```timelines``` - The aggregated events timelines group by year month
```events``` - A list of  events based on selected timeline or the current months
``eventPage`` - The event page instance
```eventPageSlug``` - The event page slug key

#### Component Usage
```twig
[Events]
monthname_field = "scope"
event_page = "volunteer/event"
event_page_slug = "{{:slug}}"
==

{% component 'Events' %}
```

### Event Component
The event component will render a single event record based on event slug or id which specified
in  the component properties.

The  component will  determine whether the event is accessible from the user by checking on
current logged in session user groups. If the user doesn't in the event's users groups a
404 error will be thrown.

#### Component page variables

```event``` - The event object

#### Component Usage
```twig
[Event]
searchBy =  "slug"
searchKey = "{{ :slug }}"
==

{% component 'Event' %}
```

### Training Component
The training component will render a single training record based on training slug or id which specified in the component properties.

Same as Event component, the  component will  determine whether the training is accessible from the user by checking on
current logged in session user groups. If the user doesn't belongs to the training's users groups a 404 error will be thrown.


#### Component page variables

```training``` - The training object

### Component usage
```twig
[Training]
searchBy = "slug"
searchKey = "{{:slug}}"
==
{% component 'Training' %}
```

### Trainings Component

The trainings component which render a list of trainings based on the current logged in member session's user group, it also supports pagination by
specify perPage properties in the trainings component.
#### Component page variables

```trainings``` - The trainings array, instance of LengthAwarePaginator
```trainingPage``` - The training page that each training record will be linked to
```trainingPageSlug``` - The training page slug key


### Component usage
```twig
[Trainings]
perPage = 10
training_page = "volunteer/training"
training_page_slug = "slug"
==
{% component 'Trainings' %}
```


### Allproducer Component

The AllProducer component render a list of producers, a limit can be specified in the components properties.

#### Component page variables
```producers``` - The producers array
```producerPage``` - The producer page name that each producer records will be linked to
```producerPageSlug``` - The producer page slug key

### Component usage
```twig
[AllProducer]
include_categories = 1
limit = 0
producer_page = "producer/products"
producer_page_slug = "slug"
==
{% component 'AllProducer' %}
```

### FaqCategories Component

The FaqCategories provide a collection of faqs categories with loaded faqs based on the selected faq category.

#### Component page variables

```categories``` - List of faq categories
```faqs``` - A collection of faqs based on the current selected faq category.

### Component usage
```twig
[FaqCategories]
category_parameter_key = "category"
==
{% component 'FaqCategories' %}
```

#### ImageSlider Component
The ImageSlider component render an image slider markup based on the label specified in the component properties.

```twig
[ImageSlider]
label = "homepage-slider"
==
{% component 'ImageSlider' %}
```

#### VideoSection Component
The VideoSection component render a video player with a set of videos based on the video playlist specified in component
properties.

#### Component page variables
```videos``` - The videos array
```playlist``` - The VideoPlaylist object

### Component usage
```twig
[VideoSection]
playlist_name = "playlist"
autoplay  =  1
==
{% component 'VideoSection' %}
```



