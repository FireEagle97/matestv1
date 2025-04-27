flowchart TD
    Start[Start] --> RoleSelect[Select Role]
    RoleSelect --> Producer[Producer]
    RoleSelect --> User[User]
    RoleSelect --> Admin[Administrator]

    subgraph Producer Flow
        Producer --> PAuth[Register or Login]
        PAuth --> PEmailVer[Email Verification]
        PEmailVer --> PProfile[Setup Profile]
        PProfile --> PDashboard[Producer Dashboard]
        PDashboard --> Upload[Upload Movie]
        Upload --> PMeta[Enter Metadata]
        PMeta --> PProof[Upload Proof of Ownership]
        PProof --> AdminReview[Await Admin Review]
        AdminReview --> |Approved| Live[Movie Live]
        AdminReview --> |Rejected| Revision[Revise or Delete]
        PDashboard --> PayoutSettings[Manage Payment Settings]
        PDashboard --> Earnings[Earnings and Analytics]
    end

    subgraph User Flow
        User --> UAuth[Register or Login]
        UAuth --> UEmailVer[Email Verification]
        UEmailVer --> UProfile[Setup Profile]
        UProfile --> Browse[Browse Movies]
        Browse --> Filter[Apply Filters]
        Filter --> SelectMovie[Select Movie]
        SelectMovie --> Checkout[Checkout]
        Checkout --> Payment[Process Payment]
        Payment --> Library[Add to Library]
        Library --> Stream[Stream Movie]
    end

    subgraph Admin Flow
        Admin --> AAuth[Login]
        AAuth --> ADashboard[Admin Dashboard]
        ADashboard --> ReviewProducers[Review Producer Registrations]
        ADashboard --> ReviewMovies[Review Movie Uploads]
        ReviewMovies --> MetadataCheck[Check Metadata]
        ReviewMovies --> OwnershipCheck[Check Proof of Ownership]
        ADashboard --> PayoutRequests[Manage Payout Requests]
        ADashboard --> Featured[Manage Featured Movies]
        ADashboard --> Analytics[View Analytics]
        ADashboard --> Notifications[Send Notifications]
    end