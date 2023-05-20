I am pleased to present my Laravel-based API project.
The API provide the functionality to create users, associate multiple projects with multiple users and multiple labels with multiple projects.

# What inside
**Laravel**

**MySQL**

**Docker Compose**

# How to use

Create Users
```
POST /api/users

{
    "users": [
        {
            "username": "name1",
            "email": "user_email1",
            "country_code": "UA"
        },
        {
            "username": "name2",
            "email": "user_email2",
            "country_code": "BV"
        }
    ]
}
```

Update Users
```
PUT /api/users

Authorization: Bearer jwt

{
    "users": [
        {
            "username": "name1",
            "email": "user_email1",
            "country_code": "UA"
        },
        {
            "username": "name2",
            "email": "user_email2",
            "country_code": "WF"
        }
    ]
}
```
Delete Users
```
DELETE /api/users

Authorization: Bearer jwt

{
    "users": [
        {
            "email": "user_email1"
        },
        {
            "email": "user_email2"
        }
    ]
}
```
Get List Of Users
```
GET /api/users

Authorization: Bearer jwt

```
Create Labels
```
POST /api/labels

Authorization: Bearer jwt

{
    "labels": [
        {
            "name": "label1"
        },
        {
            "name": "label2"
        },
        {
            "name": "label3"
        }
    ]
}
```
Link Labels To Projects
```
PUT /api/link-labels

Authorization: Bearer jwt

{
    "labels": [
        {
            "name": "label1",
            "projects": [
                {
                    "name": "project1"
                },
                {
                    "name": "project2"
                }
            ]
        },
        {
            "name": "label2",
            "projects": [
                {
                    "name": "project1"
                }
            ]
        }
    ]
}
```
Delete Labels
```
DELETE /api/labels

Authorization: Bearer jwt

{
    "labels": [
        {
            "name": "label1"
        },
        {
            "name": "label2"
        }
    ]
}
```
Get List Of Labels
```
GET /api/labels

Authorization: Bearer jwt
```
Create Projects
```
POST /api/projects

Authorization: Bearer jwt

{
    "projects": [
        {
            "name": "project1",
            "members": [
                    {
                        "email": "email1"
                    }
                ],
            "labels": [
                {
                    "name": "label1"
                },
                {
                    "name": "label2"
                }
            ]
        },
       {
            "name": "project2",
            "members": [
                    {
                        "email": "email2"
                    }
                ],
            "labels": [
                {
                    "name": "label1"
                },
                {
                    "name": "label2"
                }
            ]
        }
    ]
}
```
Update Projects
```
PUT /api/projects

Authorization: Bearer jwt

{
    "projects": [
        {
            "name": "project1",
            "members": [
                    {
                        "email": "email1"
                    }
                ],
            "labels": [
                {
                    "name": "label1"
                },
                {
                    "name": "label2"
                }
            ]
        },
       {
            "name": "project2",
            "members": [
                    {
                        "email": "email2"
                    }
                ],
            "labels": [
                {
                    "name": "label1"
                },
                {
                    "name": "label2"
                }
            ]
        }
    ]
}
```
Delete Projects
```
DELETE /api/projects

Authorization: Bearer jwt

{
    "projects": [
        {
            "name": "project99"
        },
        {
            "name": "project999"
        }
    ]
}
```
Link Projects To Users
```
PUT /api/link-projects

Authorization: Bearer jwt

{
    "projects": [
        {
            "name": "project1",
            "users": [
                {
                "email": "user_email1"
                }
            ]
        },
        {
            "name": "project2",
            "users": [
                {
                "email": "user_email1"
                }
            ]
        }
    ]
}
```
Get List Of Projects
```
GET /api/projects

Authorization: Bearer jwt
```



