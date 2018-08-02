## Maropost Esp package

### Installation
1. Be sure that private packagist is set up because both dependencies are cached there.
* See packagist.com for further instructions
2. ``` composer install ``` 

### Notes
* This package fits with rxmg/esp-interface specs for connecting to an ESP
* There is also a RawPhpVersion of this package that follows the same specs but take primitive params instead of Laravel Collections


### Notes on posting to Maripost contacts endpoint
* Maripost returns the record for a dupe instead of a dupe error message.
* Maripost checks email syntax validity and returns error for invalid emails:
```
    {
        "email": [
            "is invalid"
        ]
    }
```

*  A success response from Maripost:
```
{
    "id": 941,
    "account_id": 1377,
    "email": "test2@email.com",
    "first_name": "test",
    "last_name": "email",
    "created_at": "2018-08-01T14:07:34.000-04:00",
    "updated_at": "2018-08-01T14:07:34.000-04:00"
}
```