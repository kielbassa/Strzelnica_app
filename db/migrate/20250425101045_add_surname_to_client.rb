class AddSurnameToClient < ActiveRecord::Migration[8.0]
  def change
    add_column :clients, :surname, :string
  end
end
