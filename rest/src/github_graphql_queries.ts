export const GITHUB_ORGANIZATION_BY_NAME = function (organization_name: string) { 
return `{
    organization(login: "${organization_name}") {
        login
        name
        description
        email
        url
        
        websiteUrl
        avatarUrl
        
        createdAt
        updatedAt
        isVerified
        location
        
        announcement
        
        packages(first: 10) {
            totalCount
            nodes {
                name
                packageType
                latestVersion {
                    files(first: 10) {
                        nodes {
                            sha256
                            name
                            size
                            updatedAt
                            url
                        }
                    }
                    platform
                    preRelease
                    readme
                    version
                }
            }
        }
        
        repositories(first: 10) {
            totalCount
            nodes {
                name
                description
                updatedAt
                createdAt
                isArchived
                isPrivate
                isTemplate
                
                resourcePath
                homepageUrl
                sshUrl
                projectsUrl
                
                licenseInfo {
                    url
                    spdxId
                    name
                    nickname
                    description
                    conditions {
                        description
                        key
                        label
                    }
                }
                
                vulnerabilityAlerts(first: 10) {
                    nodes {
                        createdAt
                        fixedAt
                        dependencyScope
                        securityVulnerability {
                            vulnerableVersionRange
                            updatedAt
                            advisory {
                                classification
                                description
                                publishedAt
                                summary
                                updatedAt
                                updatedAt
                            }
                            
                            firstPatchedVersion {
                                identifier
                            }
                            package {
                                ecosystem
                                name
                            }
                        }
                        dependabotUpdate {
                            error {
                                title
                                body
                                errorType
                            }
                        }
                    }
                }
                repositoryTopics(first: 10) {
                    nodes {
                        topic {
                            name
                        }
                        url
                    }
                }
                releases(first: 10) {
                    nodes {
                        name
                        createdAt
                        description
                        isDraft
                        isLatest
                        isPrerelease
                        name
                        tagName
                        updatedAt
                        url
                        tag {
                            name
                        }
                        tagCommit {
                            additions
                            deletions
                            authoredDate
                            changedFilesIfAvailable
                            author {
                                avatarUrl
                                email
                                name
                            }
                        }
                        author {
                            avatarUrl
                            email
                            login
                            name
                            pronouns
                            url
                            websiteUrl
                        }
                        releaseAssets(first: 10) {
                            nodes {
                                contentType
                                createdAt
                                downloadCount
                                name
                                size
                                updatedAt
                            }
                        }
                    }
                }
                milestones(first: 10) {
                    nodes {
                        createdAt
                        closedAt
                        description
                        dueOn
                        progressPercentage
                        title
                        updatedAt
                        issues(first: 10) {
                            nodes {
                                title
                                bodyUrl
                                createdAt
                                updatedAt
                                url
                                closedAt
                                body
                                lastEditedAt
                                milestone {
                                    createdAt
                                    closedAt
                                    description
                                    dueOn
                                    progressPercentage
                                    title
                                    updatedAt
                                }
                                assignees(first: 10) {
                                    nodes {
                                        avatarUrl
                                        email
                                        login
                                        name
                                        pronouns
                                        url
                                        websiteUrl
                                    }
                                }
                                labels(first: 10) {
                                    nodes {
                                        url
                                        name
                                        color
                                        createdAt
                                        updatedAt
                                        description
                                        isDefault
                                    }
                                }
                            # comments
                            }
                        }
                    }
                }
                mentionableUsers(first: 10) {
                    nodes {
                        avatarUrl
                        email
                        login
                        name
                        pronouns
                        url
                        websiteUrl
                    }
                }
                primaryLanguage {
                    color
                    name
                }
                languages(first: 10) {
                    totalCount
                    nodes {
                        color
                        name
                    }
                }
                labels(first: 10) {
                    totalCount
                    nodes {
                        color
                        createdAt
                        description
                        name
                        url
                    }
                }
                deployments(first:10) {
                    nodes {
                    updatedAt
                    createdAt
                    updatedAt
                    description
                    environment
                    task
                    latestStatus {
                        createdAt
                        updatedAt
                        description
                        logUrl
                        environmentUrl
                        state
                        deployment {
                            createdAt
                            description
                            commit {
                                additions
                                deletions
                                authoredDate
                                changedFilesIfAvailable
                                author {
                                    avatarUrl
                                    email
                                    name
                                }
                            }
                        }
                    }
                    statuses(first: 10) {
                        nodes {
                            createdAt
                            updatedAt
                            description
                            logUrl
                            environmentUrl
                            state
                            deployment {
                                createdAt
                                description
                                commit {
                                    additions
                                    deletions
                                    authoredDate
                                    changedFilesIfAvailable
                                    author {
                                        avatarUrl
                                        email
                                        name
                                    }
                                }
                            }
                        }
                    }
                    }
                }
                collaborators(first: 10) {
                    nodes {
                        avatarUrl
                        email
                        login
                        name
                        pronouns
                        url
                        websiteUrl
                    }
                }
            }
        }
        
        teamsUrl
        teams(first: 10) {
            totalCount
            nodes {
                avatarUrl
                combinedSlug
                createdAt
                updatedAt
                description
                membersUrl
                name
                slug
                members(first: 10) {
                    nodes {
                    avatarUrl
                    email
                    login
                    name
                    pronouns
                    url
                    websiteUrl
                    }
                }
            }
        }
        
        projectsUrl
        projectsV2(first: 100) {
            totalCount
            nodes {
                number
                title
                url
                createdAt
                closedAt
                public
                readme
                shortDescription
                url
                views(first: 1) {
                    nodes {
                        name
                    }
                }
                
                # fields
                # items
                # repositories
                # workflows
            }
        }
    }
}`;
}
