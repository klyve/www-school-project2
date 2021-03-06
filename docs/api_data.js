define({ "api": [
  {
    "type": "Delete",
    "url": "/tempfile",
    "title": "Post new file to temp folder",
    "name": "Post_file_to_temp_folder_",
    "group": "File",
    "permission": [
      {
        "name": "user",
        "title": "Require authentication.",
        "description": ""
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Post file.",
          "content": "HTTP/1.1 201 Created\n{\n    data: {\n        id: 1,\n        userid: 1,\n        fname: \"fileName\",\n        size: 100,\n        mime: \"mp4\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/FilesController.php",
    "groupTitle": "File"
  },
  {
    "type": "Get",
    "url": "/graphql",
    "title": "Get model.",
    "name": "GraphQL_Get_",
    "description": "<p>Uses GraphQl to query the database for publicly available attributes on models. https://graphql.org/</p>",
    "group": "GraphQL",
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/GraphQLController.php",
    "groupTitle": "GraphQL"
  },
  {
    "type": "Post",
    "url": "/graphql",
    "title": "Get model.",
    "name": "GraphQL_Post_",
    "description": "<p>Uses GraphQl to query the database for publicly available attributes on models. https://graphql.org/</p>",
    "group": "GraphQL",
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/GraphQLController.php",
    "groupTitle": "GraphQL"
  },
  {
    "type": "post",
    "url": "/playlist/:playlistid/video",
    "title": "Add video to playlist",
    "name": "Add_video_to_playlist",
    "group": "Playlist",
    "permission": [
      {
        "name": "teacher",
        "title": "Require teacher access or higher.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "playlistid",
            "description": "<p>Playlist's unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "videoid",
            "description": "<p>Videos' unique ID.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Insert video",
          "content": "{\n    playlistid: 1,\n    videoid: 1\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>response container</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>id of created playlist</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.message",
            "description": "<p>Success message</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Inserted video",
          "content": "HTTP/1.1 201 Created\n{\n    data: {\n        message: \"Resource created\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error id mismatch:",
          "content": "HTTP/1.1 400 Bad Request\n{\n   code: 400,\n   error: 3,\n   message: 'Id mismatch. The id in the url and the body does not match',\n}",
          "type": "json"
        },
        {
          "title": "Error not found",
          "content": "HTTP/1.1 404 Not Found\n{\n   code: 404,\n   error: 1,\n   message: 'Could not find data with given parameters',\n}",
          "type": "json"
        },
        {
          "title": "Error SQL",
          "content": "HTTP/1.1 500 Internal Server Error\n{\n   code: 500,\n   error: 2,\n   message: 'Server had an error when trying to create resource in the datbase',\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/PlaylistVideosController.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "Post",
    "url": "/playlist/:playlistid",
    "title": "Change playlist.",
    "name": "Change_playlist_",
    "group": "Playlist",
    "permission": [
      {
        "name": "owner",
        "title": "Require ownership of resource.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "playlistid",
            "description": "<p>Id of playlist to change.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Id of playlist to change.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>New title of playlist.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>New description of playlist.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Change playlist",
          "content": "{\n    title: \"Math playlist\",\n    description: \"Quadratic expantion of imaginari numbers in the complex plain\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Container for response.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.message",
            "description": "<p>Human readable respons description.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Changed playlist",
          "content": "HTTP/1.1 200 OK\n{\n    data: {\n        message: \"Resource updated\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "id mismatch",
          "content": "HTTP/1.1 400 Bad Request\n{\n   code: 400,\n   error: 3,\n   message: \"Id mismatch. The id in the url and the body does not match.\",\n}",
          "type": "json"
        },
        {
          "title": "not found",
          "content": "HTTP/1.1 404 Not Found\n{\n   code: 404,\n   error: 3,\n   message: \"Could not find playlist with given playlistid and userid\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/PlaylistsController.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "Post",
    "url": "/playlist/:playlistid/reorder/",
    "title": "Change position in playlist.",
    "name": "Change_position_in_playlist_",
    "group": "Playlist",
    "permission": [
      {
        "name": "teacher",
        "title": "Require teacher access or higher.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number[]",
            "optional": false,
            "field": "reordering",
            "description": "<p>New order of playlist.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "playlistid",
            "description": "<p>Playlist's unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "reordering.id",
            "description": "<p>Id of video in playlist.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Reordered",
          "content": "HTTP/1.1 200 OK\n{\n    data: \"Resource marked for deletion\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message.</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error not found",
          "content": "HTTP/1.1 404 Not Found\n{\n   code: 404,\n   error: 1,\n   message: 'Could not find data with given parameters',\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/PlaylistVideosController.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "Post",
    "url": "/playlist",
    "title": "Create playlist.",
    "name": "Create_playlist_",
    "group": "Playlist",
    "permission": [
      {
        "name": "teacher",
        "title": "Require teacher access or higher.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>Title of playlist to create.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>Description of playlist to create.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Create playlist",
          "content": "{\n    title: \"Math playlist\",\n    description: \"Quadratic expantion of imaginari numbers in the complex plain\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Container for response.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.id",
            "description": "<p>Id of created playlist.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.message",
            "description": "<p>Human readable respons description.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Created playlist",
          "content": "HTTP/1.1 201 Created\n{\n    data: {\n        id: 1,\n        message: \"Resource created\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error SQL",
          "content": "HTTP/1.1 500 Not Found\n{\n   code: 500,\n   error: 4,\n   message: \"Server had an error when trying to create playlist\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/PlaylistsController.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "Post",
    "url": "/playlist/:playlistid",
    "title": "Delete playlist.",
    "name": "Delete_playlist_",
    "group": "Playlist",
    "permission": [
      {
        "name": "owner",
        "title": "Require ownership of resource.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "playlistid",
            "description": "<p>Id of playlist to change.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Container for response.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.message",
            "description": "<p>Human readable respons description.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Deleted playlist",
          "content": "HTTP/1.1 202 Accepted\n{\n    data: {\n        message: \"esource marked for deletion\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "not found",
          "content": "HTTP/1.1 404 Not Found\n{\n   code: 404,\n   error: 3,\n   message: \"Could not find playlist with given playlistid and userid\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/PlaylistsController.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "Delete",
    "url": "/playlist/:playlistid/tag/:tagname",
    "title": "Remove a tag from playlist.",
    "name": "Delete_playlist_tag",
    "group": "Playlist",
    "permission": [
      {
        "name": "teacher",
        "title": "Require teacher access or higher.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "playlistid",
            "description": "<p>Playlist unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Content of the tag.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "New tag added to playlist.",
          "content": "HTTP/1.1 202 OK\n{\n\tdata: \"Tag created and inserted\"\n}",
          "type": "json"
        },
        {
          "title": "Existing tag added to playlist.",
          "content": "HTTP/1.1 200 OK\n{\n\tdata: \"Delete accepted\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message.</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error user does not own playlist:",
          "content": "    HTTP/1.1 401 Unauthorized\n    {\n       code: 401,\n\t\t  error: 1,\n  \t  message: 'You do not have rights for given video',\n    }",
          "type": "json"
        },
        {
          "title": "Error playlist with tag does not exist:",
          "content": "    HTTP/1.1 404 Not Found\n    {\n       code: 404,\n\t\t  error: 3,\n  \t  message: 'Could not find resource',\n    }",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/PlaylistTagController.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "post",
    "url": "/playlist/:playlistid/tag",
    "title": "Post a new tag to be applied to the playlist.",
    "name": "Post_playlist_tag",
    "group": "Playlist",
    "permission": [
      {
        "name": "teacher",
        "title": "Require teacher access or higher.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "playlistid",
            "description": "<p>Playlist's unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Content of the tag.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Post tag:",
          "content": "{\n\tname: \"JSON\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "New tag added to the playlist.",
          "content": "HTTP/1.1 201 OK\n{\n\tdata: \"Tag created and inserted\"\n}",
          "type": "json"
        },
        {
          "title": "Existing tag added to the playlist.",
          "content": "HTTP/1.1 200 OK\n{\n\tdata: \"Tag inserted\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message.</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error user does not own playlist:",
          "content": "    HTTP/1.1 401 Unauthorized\n    {\n       code: 401,\n\t\t  error: 1,\n  \t  message: 'You do not have rights for given playlist',\n    }",
          "type": "json"
        },
        {
          "title": "Error playlist already contains the given tag:",
          "content": "    HTTP/1.1 409 Conflict\n    {\n       code: 409,\n\t\t  error: 2,\n  \t  message: 'The playlist already contains the tag',\n    }",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/PlaylistTagController.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "Delete",
    "url": "/playlist/:playlistid/video/:id",
    "title": "Remove video from playlist",
    "name": "Remove_video_from_playlist_",
    "group": "Playlist",
    "permission": [
      {
        "name": "teacher",
        "title": "Require teacher access or higher.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Videos's unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "playlistid",
            "description": "<p>Playlist's unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "data",
            "description": "<p>{Object} response container</p>"
          },
          {
            "group": "Success 200",
            "optional": false,
            "field": "id",
            "description": "<p>{Number} id of created playlist</p>"
          },
          {
            "group": "Success 200",
            "optional": false,
            "field": "message",
            "description": "<p>Success message</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Inserted video",
          "content": "HTTP/1.1 202 Accepted\n{\n    data: {\n        message: \"Resource marked for deletion\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error not found",
          "content": "HTTP/1.1 404 Not Found\n{\n   code: 404,\n   error: 1,\n   message: 'Could not find data with given parameters',\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/PlaylistVideosController.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "Post",
    "url": "/playlist/:playlistid/subscribe",
    "title": "Subscribe to playlist.",
    "name": "Subscribe_to_playlist_",
    "group": "Playlist",
    "permission": [
      {
        "name": "user",
        "title": "Require authentication.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "playlistid",
            "description": "<p>Id of playlist to subscrie to.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Subscribe to playlist",
          "content": "{\n\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Created playlist",
          "content": "HTTP/1.1 201 Created\n{\n    data: \"Subscribed to playlist\"\n}",
          "type": "json"
        },
        {
          "title": "Already subscribed",
          "content": "HTTP/1.1 200 OK\n{\n    data: \"Already Subscribed\"\n}",
          "type": "json"
        },
        {
          "title": "Resubscribed",
          "content": "HTTP/1.1 201 Created\n{\n    data: \"Resubscribed to playlist\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message.</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error Not Found",
          "content": "HTTP/1.1 404 Not Found\n{\n   code: 404,\n   error: 3,\n   message: \"Could not find playlist with given playlistid and userid\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/SubscriptionsController.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "Delete",
    "url": "/playlist/:playlistid/subscribe",
    "title": "Unsubscribe from playlist.",
    "name": "Unsubscribe_from_playlist_",
    "group": "Playlist",
    "permission": [
      {
        "name": "user",
        "title": "Require authentication.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "playlistid",
            "description": "<p>Id of playlist to subscrie to.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Delete subscription",
          "content": "{\n\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Unsubscribe",
          "content": "HTTP/1.1 202 Accepted\n{\n    data: \"Delete accepted\"\n}",
          "type": "json"
        },
        {
          "title": "Already unsubscribed",
          "content": "HTTP/1.1 200 OK\n{\n    data: \"Not subscribed\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message.</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error Not Found",
          "content": "HTTP/1.1 404 Not Found\n{\n   code: 404,\n   error: 3,\n   message: \"Could not find playlist with given playlistid and userid\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/SubscriptionsController.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "Delete",
    "url": "/user/",
    "title": "Delete current active user.",
    "name": "Delete_current_active_user_",
    "group": "User",
    "permission": [
      {
        "name": "user",
        "title": "Require authentication.",
        "description": ""
      }
    ],
    "success": {
      "examples": [
        {
          "title": "deleted user",
          "content": "HTTP/1.1 202 Accepted\n{\n    data: \"User 1 marked for deletion\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message.</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error Not Found",
          "content": "HTTP/1.1 404 Not Found\n{\n   data: \"Could not find 1\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/UsersController.php",
    "groupTitle": "User"
  },
  {
    "type": "Put",
    "url": "/user/",
    "title": "Edit current active user.",
    "name": "Edit_current_active_user_",
    "group": "User",
    "permission": [
      {
        "name": "user",
        "title": "Require authentication.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>New name for the user.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>New email for the user.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "get user",
          "content": "{\n    group: 2\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "updated user",
          "content": "HTTP/1.1 200 OK\n{\n    data: \"Updated User\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message.</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error Not Found",
          "content": "HTTP/1.1 404 Not Found\n{\n   data: \"Could not find 1\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/UsersController.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "/user/:id",
    "title": "Read data of a User",
    "version": "0.3.0",
    "name": "GetUser",
    "group": "User",
    "permission": [
      {
        "name": "admin",
        "title": "Admin access rights needed.",
        "description": "<p>Optionally you can write here further Informations about the permission.</p> <p>An &quot;apiDefinePermission&quot;-block can have an &quot;apiVersion&quot;, so you can attach the block to a specific version.</p>"
      }
    ],
    "description": "<p>Compare Verison 0.3.0 with 0.2.0 and you will see the green markers with new items in version 0.3.0 and red markers with removed items since 0.2.0.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>The Users-ID.</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Example usage:",
        "content": "curl -i http://localhost/user/4711",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>The Users-ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "registered",
            "description": "<p>Registration Date.</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "name",
            "description": "<p>Fullname of the User.</p>"
          },
          {
            "group": "Success 200",
            "type": "String[]",
            "optional": false,
            "field": "nicknames",
            "description": "<p>List of Users nicknames (Array of Strings).</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "profile",
            "description": "<p>Profile data (example for an Object)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "profile.age",
            "description": "<p>Users age.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.image",
            "description": "<p>Avatar-Image.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "options",
            "description": "<p>List of Users options (Array of Objects).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "options.name",
            "description": "<p>Option Name.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "options.value",
            "description": "<p>Option Value.</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NoAccessRight",
            "description": "<p>Only authenticated Admins can access the data.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "UserNotFound",
            "description": "<p>The <code>id</code> of the User was not found.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Response (example):",
          "content": "HTTP/1.1 401 Not Authenticated\n{\n  \"error\": \"NoAccessRight\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "server/node_modules/apidoc/example/example.js",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "/user/:id",
    "title": "Read data of a User",
    "version": "0.2.0",
    "name": "GetUser",
    "group": "User",
    "permission": [
      {
        "name": "admin",
        "title": "This title is visible in version 0.1.0 and 0.2.0",
        "description": ""
      }
    ],
    "description": "<p>Here you can describe the function. Multilines are possible.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>The Users-ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>The Users-ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "name",
            "description": "<p>Fullname of the User.</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "UserNotFound",
            "description": "<p>The <code>id</code> of the User was not found.</p>"
          }
        ]
      }
    },
    "filename": "server/node_modules/apidoc/example/_apidoc.js",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "/user/:id",
    "title": "Read data of a User",
    "version": "0.1.0",
    "name": "GetUser",
    "group": "User",
    "permission": [
      {
        "name": "admin",
        "title": "This title is visible in version 0.1.0 and 0.2.0",
        "description": ""
      }
    ],
    "description": "<p>Here you can describe the function. Multilines are possible.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>The Users-ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>The Users-ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "name",
            "description": "<p>Fullname of the User.</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "UserNotFound",
            "description": "<p>The error description text in version 0.1.0.</p>"
          }
        ]
      }
    },
    "filename": "server/node_modules/apidoc/example/_apidoc.js",
    "groupTitle": "User"
  },
  {
    "type": "Get",
    "url": "/user",
    "title": "Get current active user.",
    "name": "Get_current_active_user_",
    "group": "User",
    "permission": [
      {
        "name": "user",
        "title": "Require authentication.",
        "description": ""
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>Container for respons.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.name",
            "description": "<p>Name of user.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.email",
            "description": "<p>Email of user.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.usergroup",
            "description": "<p>Access level of the user.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.requestedgroup",
            "description": "<p>Requested access level of the user.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "got user",
          "content": "HTTP/1.1 200 OK\n{\n    data: {\n        name: \"Kruskontroll\",\n        email: \"Kruskontroll@localhost.com\",\n        usergroup: \"1\",\n        requestedgroup: \"2\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.token",
            "description": "<p>Identifier of the user.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/UsersController.php",
    "groupTitle": "User"
  },
  {
    "type": "Put",
    "url": "/user/:id/group",
    "title": "Give new group to user.",
    "name": "Give_new_group_to_user_",
    "group": "User",
    "permission": [
      {
        "name": "admin",
        "title": "Require admin access.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Identifier for the user.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "group",
            "description": "<p>New group for the user.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.token",
            "description": "<p>Identifier of the user.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "get user",
          "content": "{\n    group: 2\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>Container for respons.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.name",
            "description": "<p>Name of user.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.email",
            "description": "<p>Email of user.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.usergroup",
            "description": "<p>Access level of the user.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.requestedgroup",
            "description": "<p>Requested access level of the user.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "updated group",
          "content": "HTTP/1.1 200 OK\n{\n    data: {\n        name: \"Kruskontroll\",\n        email: \"Kruskontroll@localhost.com\",\n        usergroup: \"1\",\n        requestedgroup: \"2\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error Not Found",
          "content": "HTTP/1.1 404 Not Found\n{\n   data: \"Could not find 1\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/UsersController.php",
    "groupTitle": "User"
  },
  {
    "type": "Post",
    "url": "/user/login",
    "title": "Login as user.",
    "name": "Login_as_user",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>Email of user to login.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>User password.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Post tag:",
          "content": "{\n    name: \"useremail@kruskontroll.no\",\n    password: \"someDifficultPassword43*\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Post login.",
          "content": "HTTP/1.1 200 OK\n{\n    data: {\n        name: \"username\",\n        email: \"useremail@kruskontroll.no\",\n        usergroup: 1,\n        token: \"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiIxIiwiaXNzIjoiS3J1c0tvbnRyb2xsLmNvbSIsImV4cCI6IjIwMTgtMDUtMDQgMTM6NTI6MTEiLCJzdWIiOiIiLCJhdWQiOiIifQ.kRRskrm5F2hg8PjNzxBjLiC9iE_jJ_J9ZY10Nx6wh68\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error user does not own video with given id:",
          "content": "HTTP/1.1 200 Ok\n{\n   error: \"Username or password is invalid\",\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/AuthController.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "/user",
    "title": "Create a new User",
    "version": "0.3.0",
    "name": "PostUser",
    "group": "User",
    "permission": [
      {
        "name": "none"
      }
    ],
    "description": "<p>In this case &quot;apiErrorStructure&quot; is defined and used. Define blocks with params that will be used in several functions, so you dont have to rewrite them.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the User.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>The new Users-ID.</p>"
          }
        ]
      }
    },
    "filename": "server/node_modules/apidoc/example/example.js",
    "groupTitle": "User",
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NoAccessRight",
            "description": "<p>Only authenticated Admins can access the data.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "UserNameTooShort",
            "description": "<p>Minimum of 5 characters required.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Response (example):",
          "content": "HTTP/1.1 400 Bad Request\n{\n  \"error\": \"UserNameTooShort\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/user",
    "title": "Create a User",
    "version": "0.2.0",
    "name": "PostUser",
    "group": "User",
    "permission": [
      {
        "name": "none"
      }
    ],
    "description": "<p>In this case &quot;apiErrorStructure&quot; is defined and used. Define blocks with params that will be used in several functions, so you dont have to rewrite them.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the User.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>The Users-ID.</p>"
          }
        ]
      }
    },
    "filename": "server/node_modules/apidoc/example/_apidoc.js",
    "groupTitle": "User",
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NoAccessRight",
            "description": "<p>Only authenticated Admins can access the data.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "UserNameTooShort",
            "description": "<p>Minimum of 5 characters required.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Response (example):",
          "content": "HTTP/1.1 400 Bad Request\n{\n  \"error\": \"UserNameTooShort\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "put",
    "url": "/user/:id",
    "title": "Change a User",
    "version": "0.3.0",
    "name": "PutUser",
    "group": "User",
    "permission": [
      {
        "name": "none"
      }
    ],
    "description": "<p>This function has same errors like POST /user, but errors not defined again, they were included with &quot;apiErrorStructure&quot;</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the User.</p>"
          }
        ]
      }
    },
    "filename": "server/node_modules/apidoc/example/example.js",
    "groupTitle": "User",
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NoAccessRight",
            "description": "<p>Only authenticated Admins can access the data.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "UserNameTooShort",
            "description": "<p>Minimum of 5 characters required.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Response (example):",
          "content": "HTTP/1.1 400 Bad Request\n{\n  \"error\": \"UserNameTooShort\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "Put",
    "url": "/user/password",
    "title": "Change the user's password.",
    "name": "Put_user_password",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>Current password of user.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "newpassword",
            "description": "<p>New password of user.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Put password:",
          "content": "{\n    password: \"oldPassword43*\",\n    newpassword: \"newPassword43*\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Put password.",
          "content": "HTTP/1.1 204 No content\n{\n\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "password is wrong:",
          "content": "HTTP/1.1 401 Unauthorized\n{\n   code: 401,\n   error: 2,\n   message: 'Invalid password',\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/AuthController.php",
    "groupTitle": "User"
  },
  {
    "type": "Post",
    "url": "/user/refresh",
    "title": "Refresh token identifying user.",
    "name": "Refresh_token",
    "group": "User",
    "parameter": {
      "examples": [
        {
          "title": "Post tag:",
          "content": "{\n    \n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Post login.",
          "content": "HTTP/1.1 200 OK\n{\n    \"data\": {\n        \"key\": \"1\",\n        \"value\": \"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiIxIiwiaXNzIjoiS3J1c0tvbnRyb2xsLmNvbSIsImV4cCI6IjIwMTgtMDUtMDQgMTM6NTI6MTEiLCJzdWIiOiIiLCJhdWQiOiIifQ.kRRskrm5F2hg8PjNzxBjLiC9iE_jJ_J9ZY10Nx6wh68\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error user does not own video with given id:",
          "content": "HTTP/1.1 200 Ok\n{\n   \"data\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/AuthController.php",
    "groupTitle": "User"
  },
  {
    "type": "Post",
    "url": "/user/register",
    "title": "Register a new user.",
    "name": "Register_user",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>Email of user to register.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>User password.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Post tag:",
          "content": "{\n    email: \"useremail@kruskontroll.no\"\n    password: \"someDifficultPassword43*\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Post login.",
          "content": "HTTP/1.1 200 OK\n{\n    data: {\n        name: \"username\",\n        email: \"useremail@kruskontroll.no\",\n        usergroup: 1,\n        token: \"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiIxIiwiaXNzIjoiS3J1c0tvbnRyb2xsLmNvbSIsImV4cCI6IjIwMTgtMDUtMDQgMTM6NTI6MTEiLCJzdWIiOiIiLCJhdWQiOiIifQ.kRRskrm5F2hg8PjNzxBjLiC9iE_jJ_J9ZY10Nx6wh68\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/AuthController.php",
    "groupTitle": "User"
  },
  {
    "type": "Get",
    "url": "/testtoken",
    "title": "Update token.",
    "name": "update_token",
    "group": "User",
    "success": {
      "examples": [
        {
          "title": "Put password.",
          "content": "HTTP/1.1 200 OK\n{\n    token : eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiIxIiwiaXNzIjoiS3J1c0tvbnRyb2xsLmNvbSIsImV4cCI6IjIwMTgtMDUtMDQgMTE6NTM6NDUiLCJzdWIiOiIiLCJhdWQiOiIifQ.EbB3SXuKqJJ2Bn6hpAIlZYJJPK010816dtro1z7Nxho\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/AuthController.php",
    "groupTitle": "User"
  },
  {
    "type": "Put",
    "url": "/video/:videoid/comment/:commentid",
    "title": "Edit a video comment.",
    "name": "Change_a_comment_",
    "group": "Video",
    "permission": [
      {
        "name": "owner",
        "title": "Require ownership of resource.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "videoid",
            "description": "<p>ID of video to post comment to.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "commentid",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>New comment message to post.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Post comment:",
          "content": "{\n    content: \"Commenting the video\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Edit comment.",
          "content": "     HTTP/1.1 200 Created\n     {\n\t\t\tdata: \"Comment updated\"\n     }",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Not owner",
          "content": "    HTTP/1.1 401 Unauthorized\n    {\n\t\t code: 401,\n\t\t error: 1,\n      message: \"You do not have rights for given comment\"\n    }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/CommentsController.php",
    "groupTitle": "Video"
  },
  {
    "type": "post",
    "url": "/video/:videoid/rate",
    "title": "Change users rating of video.",
    "name": "Change_users_rating_of_video_",
    "group": "Video",
    "permission": [
      {
        "name": "user",
        "title": "Require authentication.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "videoid",
            "description": "<p>video's unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "rating",
            "description": "<p>Rating of the video.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "put rating:",
          "content": "{\n    rating: 1\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "put rating.",
          "content": "HTTP/1.1 200 OK\n{\n    data: \"Rating created\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message.</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error not found",
          "content": "HTTP/1.1 404 Not Found\n{\n   code: 404,\n   error: 1,\n   message: 'Could not find data with given parameters',\n}",
          "type": "json"
        },
        {
          "title": "Error SQL",
          "content": "HTTP/1.1 500 Conflict\n{\n   code: 500,\n   error: 2,\n   message: 'Server had an error when trying to create resource in the datbase',\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/VideoRatingsController.php",
    "groupTitle": "Video"
  },
  {
    "type": "Post",
    "url": "/video/:videoid/comment",
    "title": "Post new comment on a video.",
    "name": "Comment_on_video_",
    "group": "Video",
    "permission": [
      {
        "name": "user",
        "title": "Require authentication.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "videoid",
            "description": "<p>ID of video to post comment to.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>Comment message to post.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Post comment:",
          "content": "{\n    content: \"Commenting the video\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Identifier for posted comment.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Success message.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Post new comment.",
          "content": "     HTTP/1.1 201 Created\n     {\n\t\t\tid: 7,\n\t\t\tmessage: \"Comment created and inserted\"\n     }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/CommentsController.php",
    "groupTitle": "Video"
  },
  {
    "type": "Delete",
    "url": "/video/:videoid/comment/:commentid",
    "title": "Delete a comment.",
    "name": "Delete_a_comment_",
    "group": "Video",
    "permission": [
      {
        "name": "owner",
        "title": "Require ownership of resource.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "videoid",
            "description": "<p>ID of video to post comment to.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "commentid",
            "description": ""
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Delete comment.",
          "content": "     HTTP/1.1 202 Created\n     {\n\t\t\tdata: \"Comment updated\"\n     }",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Not owner",
          "content": "    HTTP/1.1 401 Unauthorized\n    {\n\t\t code: 401,\n\t\t error: 1,\n      message: \"You do not have rights for given comment\"\n    }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/CommentsController.php",
    "groupTitle": "Video"
  },
  {
    "type": "post",
    "url": "/video/:videoid/rate",
    "title": "Delete users rating of video.",
    "name": "Delete_users_rating_of_video_",
    "group": "Video",
    "permission": [
      {
        "name": "user",
        "title": "Require authentication.",
        "description": ""
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "videoid",
            "description": "<p>video's unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "delte accepted.",
          "content": "HTTP/1.1 202 Accepted\n{\n    data: \"Rating deletedd\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Success message.</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error not found",
          "content": "HTTP/1.1 404 Not Found\n{\n   code: 404,\n   error: 1,\n   message: 'Could not find data with given parameters',\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>Http status code.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Internal error usefull for debug.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Error message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/VideoRatingsController.php",
    "groupTitle": "Video"
  },
  {
    "type": "Delete",
    "url": "/video/:videoid/tag/:tagname",
    "title": "Remove a tag from video.",
    "name": "Delete_video_tag",
    "group": "Video",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "videoid",
            "description": "<p>Videos unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>content of the tag.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "When new tag is added to the video.",
          "content": "HTTP/1.1 202 OK\n{\n\tdata: \"Tag created and inserted\"\n}",
          "type": "json"
        },
        {
          "title": "When existing tag is added to the video.",
          "content": "HTTP/1.1 200 OK\n{\n\tdata: \"Delete accepted\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error user does not own video with given id:",
          "content": "    HTTP/1.1 401 Unauthorized\n    {\n       code: 401,\n\t\t  error: 1,\n  \t  message: 'You do not have rights for given video',\n    }",
          "type": "json"
        },
        {
          "title": "Error video with tag does not exist:",
          "content": "    HTTP/1.1 404 Not Found\n    {\n       code: 404,\n\t\t  error: 3,\n  \t  message: 'Could not find resource',\n    }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/VideoTagsController.php",
    "groupTitle": "Video"
  },
  {
    "type": "post",
    "url": "/video/:videoid/tag",
    "title": "Post a new tag to be applied to the video.",
    "name": "Post_video_tag",
    "group": "Video",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "videoid",
            "description": "<p>Videos unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>content of the tag.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "/video/:videoid/tag {json} Post tag:",
          "content": "{\n\tname: \"JSON\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "When new tag is added to the video.",
          "content": "HTTP/1.1 201 OK\n{\n\tdata: \"Tag created and inserted\"\n}",
          "type": "json"
        },
        {
          "title": "When existing tag is added to the video.",
          "content": "HTTP/1.1 200 OK\n{\n\tdata: \"Tag inserted\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error user does not own video with given id:",
          "content": "    HTTP/1.1 401 Unauthorized\n    {\n       code: 401,\n\t\t  error: 1,\n  \t  message: 'You do not have rights for given video',\n    }",
          "type": "json"
        },
        {
          "title": "Error video already contains the given tag:",
          "content": "    HTTP/1.1 409 Conflict\n    {\n       code: 409,\n\t\t  error: 2,\n  \t  message: 'The video already contains the tag',\n    }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "server/src/App/Controllers/VideoTagsController.php",
    "groupTitle": "Video"
  },
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "server/node_modules/apidoc/template/main.js",
    "group": "_home_flero_school_webTechnology_project_2_imt2291_prosjekt2_v2018_server_node_modules_apidoc_template_main_js",
    "groupTitle": "_home_flero_school_webTechnology_project_2_imt2291_prosjekt2_v2018_server_node_modules_apidoc_template_main_js",
    "name": ""
  }
] });
