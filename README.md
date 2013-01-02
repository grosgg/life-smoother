life-smoother
=================
An money application to make life easy.
*(This project is mostly for training purposes.)*

Feature objectives for v1.0
----------------------

### Analytics:
* Date selection
* Date presets
* Expenses vs incomes (line)
* Expenses by category (pie)
* Expenses by shop (pie)
* Expenses by user (pie)

### Estimations:
* Automatic estimation
    * Average on past months (based on 1, 3, 6, 9 or 12 months)
    * Simulation on upcoming months (forecasting for 6, 12, 18 and 24 months)
    * Details by category, shop, user + savings
    * Third column: calculated savings

* Manual estimation
    * Add recurring lines of expenses and incomes
         * Set recurring period (day, week, month, year) x times (for example: every 2 weeks)
    * Add exceptionnal expense lines
    * Save and load as preset
    * Simulation on upcoming months (forecasting for 6, 12, 18 and 24 months)
    * Details by category, shop, user + savings


### Groups:
* Show operations by groups
* Users can only be from one group

### Bank listing import:
* Uploader
* Summarizes data imports and ask for validation
* Display data imports by date
* Auto guess what shop
* User based guess shop, category and user
* Don't import doubles (if same amount, shop and day)
* Store already imported lines (table importedLines: importId, importLine)

### Choose defaults:
* Shop
* Category for Shop
* Category
* User

### Parsing Rules:
* Create pattern
* Choose what to match (shop, category, user…)

### Sonata Admin:
* All entities
* All users
* Convenient methods:
    * Reset ProcessedLines for Import
