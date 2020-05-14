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


### Event Component
The event component grab a single record of event based on either slug  or id depends on the searchKey property value.
If there is a user logged  in session, the events records is narrow down to user group events prior on searching one
single events based on slug or id.

#### Component page variables

```timelines``` - The aggregated events timelines group by year month
```events``` - A list of  events based on selected timeline or the current months
#### Component Usage
```twig
[Event]
searchBy = "slug"
searchKey = "{{ :slug }}"
==

<h5>{{event.title}}</h5>
```

### Events Component
The events component grab a list of events based.

#### Component Usage
```twig
[Event]
searchBy = "slug"
searchKey = "{{ :slug }}"
==

<h5>{{event.title}}</h5>
```

