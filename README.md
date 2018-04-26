# kaystrobach/liquefy

This project aims to provide a clean way to separate the views and the controllers, so that frontend devs
may just work on the rendering on the page with provided sample data.

To get started you need php7 and composer. 

## Getting Started

* install php7
* install composer

Put your files in the following folders:

* Resources/Private
    * Layouts
    * Templates
    * Partials
* Resources/Public
    * \*
    
This will result in a `Web` directory created in the root of your project
containing the rendered private and public resources.


## Running the tests

```bash
composer test
```

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Kay Strobach** - *Initial work* - [kaystrobach](https://github.com/kaystrobach)

See also the list of [contributors](https://github.com/kaystrobach/liquefy/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
