# Logistic Price Calculation API

## Table of Contents
- [Introduction](#introduction)
- [Technologies Used](#technologies-used)
- [Features](#features)
- [Setup and Installation](#setup-and-installation)
- [Testing](#testing)

## Introduction
The Logistic Price Calculation API is a RESTful service that calculates transport prices based on route details provided in the request. The API uses the Google Directions API to determine the total distance between multiple addresses and calculates the price for different vehicle types based on this distance.

## Technologies Used
- **PHP 8.2**
- **Lumen Framework**
- **MongoDB**
- **cURL** for HTTP requests
- **Google Directions API**
- **PHPUnit** for testing
- **Redis** for caching

## Features
- **Transport Price Calculation:** Calculate prices for different vehicle types based on the total distance between multiple addresses.
- **Basic Authentication:** Secures the API with Basic Authentication using an API key.
- **Input Validation:** Validates the input to ensure addresses exist in the database.
- **Error Handling:** Provides detailed error messages for invalid requests or API errors.

## Setup and Installation
- Clone the repository
- docker-compose up --build

## Testing
- docker exec -it logistics_app ./vendor/bin/phpunit
- You can find Postman collection in root directory
