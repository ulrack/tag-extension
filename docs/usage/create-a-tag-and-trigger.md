# Ulrack Tag Extension - Create a tag and trigger

With this package two new service definitions are added to an Ulrack project.

## Triggers

Triggers are registered to a service which, when invoked, will call the
attached tags. This can be usefull when multiple instances need to be added
dynamically to a service. The trigger can also be invoked and the result can
be passed to a service as a parameter.

To create a trigger, add a file to `configuration/triggers` with the following
contents:
```json
{
    "my.trigger": {
        "service": "services.my.service"
    }
}
```

When `services.my.service` is invoked all tags attached to
`triggers.my.trigger` will be invoked. The `service` field is optional. When it
is left out, the trigger will only be invoked, when called directly.

## Tags

Tags are used to connect services to a trigger. When that trigger is invoked
all tags are collected and invoked.

Tags define which service is attached to which trigger. For example, an
`invocation` which adds a logger to an object through a method, can be
registered to the object when the actual object is created, by defining that
through the `triggers` and `tags`.

To create a tag, create a file in `configuration/tags` with the following content:
```json
{
    "my.tag": {
        "trigger": "triggers.my.trigger",
        "service": "invocations.add.logger"
    }
}
```

After clearing the cache and invoking the `services.my.service` service, will
trigger `tag` and thus invoking `invocations.add.logger`.

## Further reading

[Back to usage index](index.md)

[Installation](installation.md)
