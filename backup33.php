
<?php
// Prepare the SQL statements
		
		$checkExistingQuery = "SELECT id FROM tenants WHERE id != ?";
		$checkTenantQuery = "SELECT id FROM tenants WHERE firstname = ? AND lastname = ? AND middlename = ? AND contact = ? AND id != ?";
		$insertQuery = "INSERT INTO tenants (id,firstname, lastname, middlename, email, contact) VALUES (?, ?, ?, ?, ?, ?)";
		$updateQuery = "UPDATE tenants SET firstname = ?, lastname = ?, middlename = ?, email = ?, contact = ?, house_id = ? WHERE id = ?";
		
	
		try {
			// Start the transaction
			$this->db->begin_transaction();
	
			// Check if a tenant with the same house_id already exists, excluding the current record if updating
			$stmt = $this->db->prepare($checkExistingQuery);
			$stmt->bind_param('ii',$tenantid); // Exclude the current record by checking 'id != ?'
			$stmt->execute();
			$stmt->store_result();
			$existing_count = $stmt->num_rows;
			$stmt->close();
	
			if ($existing_count > 0) {
				// Check if the tenant already exists with the given details, excluding the current record if updating
				$stmt = $this->db->prepare($checkTenantQuery);
				$stmt->bind_param($tenantid, $firstname, $lastname, $middlename, $contact); // Exclude the current record by checking 'id != ?'
				$stmt->execute();
				$stmt->store_result();
				$tenant_exists = $stmt->num_rows > 0;
				$stmt->close();
	
				if ($tenant_exists) {
					$this->db->rollback();
					return 2; // Tenant already exists
				}
			}
	
			// Insert or update tenant
			if (empty($id)) {
				// Insert new tenant
				$stmt = $this->db->prepare($insertQuery);
				$stmt->bind_param($tenantid, $firstname, $lastname, $middlename, $email, $contact); // Corrected parameter count and types
		
				
				$insert_query = $this->db->query("INSERT INTO house_renting (tenant_id,house_id,date_in) 
		VALUES ('$tenantid','$house_id','$date_created')");
				
				
			} else {
				// Update existing tenant
				$stmt = $this->db->prepare($updateQuery);
				$stmt->bind_param($tenantid, $firstname, $lastname, $middlename, $email, $contact, $house_id); // Add 'id' to parameters for updating
			}
	
			$result = $stmt->execute();
			$stmt->close();
	
			if ($result) {
				$this->db->commit();
				return 1; // Success (inserted or updated)
			} else {
				$this->db->rollback();
				return 0; // Failure
			}
		} catch (Exception $e) {
			$this->db->rollback();
			error_log("Error saving tenant: " . $e->getMessage());
			return 0; // Failure
		}
		?>