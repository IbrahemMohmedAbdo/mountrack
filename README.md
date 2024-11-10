# Product Filter Service

A Laravel project for managing and filtering a large set of products based on attributes like price, quantity, color, fuel type, and engine type. Designed to support high-performance search with caching and optimized data loading.

## Features
- Products Table: Stores product information including name, price, color, quantity, fuel type, and engine type.
- Factory and Seeder: Generates 100,000 sample products for testing.
- Filter API: Allows range and multi-select filtering on price, quantity, color, and fuel type.
- Filter Values API: Provides a list of distinct colors, fuel types, and engine types for the frontend filters.
- Scheduled Cache Refresh: Refreshes filter values daily at 2:00 AM to keep the data up-to-date.

## Installation

### Prerequisites
- PHP 8.0+
- Composer
- Laravel

### Setup
1. Clone the repository:
   `bash
   git clone url 
