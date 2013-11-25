#Dog-Cat-Example-Softdeletes

This example show the usage of single-table-inheritance model with softdeletes. In this examples we have the `animals` table. we currently have 2 type of animals:

- Dog
- Cat

The Dog have the following attributes: "name","food","toy","collar". While the Cat has: "name","food","toy","bedding". You can check the migration file to see how is structured the database. 

If you use Dog or Cat class you operate on the `animals` table but you fetch and save only the attributes associated to the model.
If you try to save or fetch the data you fetch the data only of the given model. In case you want to fetch the data of all the models regardless of the type you should create custom queries.