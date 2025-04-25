class AddAgeToClient < ActiveRecord::Migration[8.0]
  def change
    add_column :clients, :age, :integer
  end
end
